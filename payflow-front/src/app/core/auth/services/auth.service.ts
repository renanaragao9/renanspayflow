import { inject, Injectable, signal } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import { Observable, tap } from 'rxjs';
import {
  ApiResponse,
  AuthLoginResponse,
  LoginCredentials,
  RegisterCredentials,
  User,
} from '../models/auth.model';
import { environment } from '../../../../environments/environment';

@Injectable({ providedIn: 'root' })
export class AuthService {
  private readonly http = inject(HttpClient);
  private readonly router = inject(Router);
  private readonly TOKEN_KEY = 'auth_token';

  readonly currentUser = signal<User | null>(null);

  get token(): string | null {
    return localStorage.getItem(this.TOKEN_KEY);
  }

  isAuthenticated(): boolean {
    return !!this.token;
  }

  login(credentials: LoginCredentials): Observable<AuthLoginResponse> {
    return this.http.post<AuthLoginResponse>(`${environment.apiUrl}/auth/login`, credentials).pipe(
      tap((response) => {
        localStorage.setItem(this.TOKEN_KEY, response.data.token);
        this.currentUser.set(response.data.me);
      }),
    );
  }

  register(data: RegisterCredentials): Observable<ApiResponse<{ user: User }>> {
    return this.http.post<ApiResponse<{ user: User }>>(`${environment.apiUrl}/auth/register`, data);
  }

  loadCurrentUser(): Observable<ApiResponse<User>> {
    return this.http
      .get<ApiResponse<User>>(`${environment.apiUrl}/v1/auth/me`)
      .pipe(tap((response) => this.currentUser.set(response.data)));
  }

  logout(): void {
    this.http
      .post(`${environment.apiUrl}/v1/auth/logout`, {})
      .subscribe({ complete: () => this.clearAuth(), error: () => this.clearAuth() });
  }

  clearAuth(): void {
    localStorage.removeItem(this.TOKEN_KEY);
    this.currentUser.set(null);
    this.router.navigate(['/auth/login']);
  }
}

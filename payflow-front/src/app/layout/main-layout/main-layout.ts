import { Component, inject, OnInit } from '@angular/core';
import { RouterLink, RouterLinkActive, RouterOutlet } from '@angular/router';
import { AuthService } from '../../core/auth/services/auth.service';

@Component({
  selector: 'app-main-layout',
  imports: [RouterOutlet, RouterLink, RouterLinkActive],
  templateUrl: './main-layout.html',
})
export class MainLayout implements OnInit {
  private readonly authService = inject(AuthService);

  readonly currentUser = this.authService.currentUser;

  ngOnInit(): void {
    if (!this.currentUser()) {
      this.authService.loadCurrentUser().subscribe();
    }
  }

  logout(): void {
    this.authService.logout();
  }
}

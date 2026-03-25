import { Component, inject, signal } from '@angular/core';
import { FormBuilder, ReactiveFormsModule, Validators } from '@angular/forms';
import { Router, RouterLink } from '@angular/router';
import { AuthService } from '../../../core/auth/services/auth.service';

@Component({
  selector: 'app-register',
  imports: [ReactiveFormsModule, RouterLink],
  templateUrl: './register.html',
})
export class Register {
  private readonly authService = inject(AuthService);
  private readonly router = inject(Router);
  private readonly fb = inject(FormBuilder);

  readonly loading = signal(false);
  readonly error = signal<string | null>(null);
  readonly success = signal<string | null>(null);

  readonly form = this.fb.group(
    {
      name: ['', [Validators.required, Validators.minLength(2)]],
      email: ['', [Validators.required, Validators.email]],
      password: ['', [Validators.required, Validators.minLength(8)]],
      password_confirmation: ['', [Validators.required]],
    },
    { validators: this.passwordMatchValidator },
  );

  private passwordMatchValidator(group: import('@angular/forms').AbstractControl) {
    const password = group.get('password')?.value;
    const confirm = group.get('password_confirmation')?.value;
    return password === confirm ? null : { passwordMismatch: true };
  }

  submit(): void {
    if (this.form.invalid) {
      this.form.markAllAsTouched();
      return;
    }

    this.loading.set(true);
    this.error.set(null);

    const { name, email, password, password_confirmation } = this.form.value;

    this.authService
      .register({
        name: name!,
        email: email!,
        password: password!,
        password_confirmation: password_confirmation!,
      })
      .subscribe({
        next: (response) => {
          this.success.set(response.message);
          setTimeout(() => this.router.navigate(['/auth/login']), 2500);
        },
        error: (err) => {
          this.error.set(err.error?.message ?? 'Erro ao cadastrar. Tente novamente.');
          this.loading.set(false);
        },
      });
  }
}

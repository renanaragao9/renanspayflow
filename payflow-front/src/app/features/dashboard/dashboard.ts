import { Component, inject } from '@angular/core';
import { AuthService } from '../../core/auth/services/auth.service';

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.html',
})
export class Dashboard {
  private readonly authService = inject(AuthService);
  readonly currentUser = this.authService.currentUser;
}

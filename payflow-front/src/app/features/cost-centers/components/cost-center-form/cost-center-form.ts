import {
  ChangeDetectionStrategy,
  Component,
  computed,
  effect,
  inject,
  input,
  output,
  signal,
} from '@angular/core';
import { ReactiveFormsModule, FormBuilder, Validators } from '@angular/forms';
import { CostCenter } from '../../models/cost-center';
import { CostCenterPayload } from '../../models/cost-center-payload';
import { CostCenterService } from '../../services/cost-center.service';

@Component({
  selector: 'app-cost-center-form',
  changeDetection: ChangeDetectionStrategy.OnPush,
  imports: [ReactiveFormsModule],
  templateUrl: './cost-center-form.html',
})
export class CostCenterForm {
  private readonly fb = inject(FormBuilder);
  private readonly service = inject(CostCenterService);

  readonly costCenter = input<CostCenter | null>(null);
  readonly saved = output<CostCenter>();
  readonly cancelled = output<void>();
  readonly error = signal<string | null>(null);
  readonly loading = signal(false);

  readonly isEditing = computed(() => !!this.costCenter());
  readonly title = computed(() =>
    this.isEditing() ? 'Editar Centro de Custo' : 'Novo Centro de Custo',
  );

  readonly form = this.fb.group({
    name: ['', [Validators.required, Validators.minLength(3), Validators.maxLength(255)]],
    type: ['', [Validators.required, Validators.maxLength(255)]],
    dueDate: ['' as string | null],
  });

  constructor() {
    effect(
      () => {
        const cc = this.costCenter();
        if (cc) {
          this.form.patchValue({
            name: cc.name,
            type: cc.type,
            dueDate: cc.dueDate ? cc.dueDate.substring(0, 10) : '',
          });
        } else {
          this.form.reset({ name: '', type: '', dueDate: '' });
        }
        this.error.set(null);
      },
      { allowSignalWrites: true },
    );
  }

  submit(): void {
    if (this.form.invalid) {
      this.form.markAllAsTouched();
      return;
    }

    this.loading.set(true);
    this.error.set(null);

    const { name, type, dueDate } = this.form.value;
    const payload: CostCenterPayload = {
      name: name!,
      type: type!,
      dueDate: dueDate || null,
    };

    const cc = this.costCenter();
    const request$ = cc ? this.service.update(cc.id, payload) : this.service.store(payload);

    request$.subscribe({
      next: (response) => {
        this.loading.set(false);
        this.saved.emit(response.data);
      },
      error: (err) => {
        this.error.set(err.error?.message ?? 'Erro ao salvar. Tente novamente.');
        this.loading.set(false);
      },
    });
  }

  cancel(): void {
    this.cancelled.emit();
  }

  fieldInvalid(field: string): boolean {
    const ctrl = this.form.get(field);
    return !!(ctrl?.invalid && ctrl.touched);
  }
}

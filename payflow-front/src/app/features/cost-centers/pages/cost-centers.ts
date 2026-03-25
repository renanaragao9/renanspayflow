import {
  ChangeDetectionStrategy,
  Component,
  computed,
  inject,
  OnInit,
  signal,
} from '@angular/core';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { CostCenter } from '../models/cost-center';
import { CostCenterService } from '../services/cost-center.service';
import { PaginationMeta } from '../../../shared/models/pagination.model';
import { CostCenterForm } from '../components/cost-center-form/cost-center-form';
import { formatDateBR } from '../../../shared/utils/format-date-br.util';

@Component({
  selector: 'app-cost-centers',
  changeDetection: ChangeDetectionStrategy.OnPush,
  imports: [CostCenterForm, FormsModule, CommonModule],
  templateUrl: './cost-centers.html',
})
export class CostCenters implements OnInit {
  private readonly service = inject(CostCenterService);

  readonly items = signal<CostCenter[]>([]);
  readonly meta = signal<PaginationMeta | null>(null);
  readonly error = signal<string | null>(null);
  readonly loading = signal(false);

  readonly searchInput = signal('');
  readonly currentPage = signal(1);

  readonly formData = signal<CostCenter | 'create' | null>(null);
  readonly isFormOpen = computed(() => this.formData() !== null);
  readonly editingItem = computed(() => {
    const v = this.formData();
    return v !== 'create' ? v : null;
  });

  readonly deletingItem = signal<CostCenter | null>(null);
  readonly deleteLoading = signal(false);
  readonly deleteError = signal<string | null>(null);

  readonly lastPage = computed(() => this.meta()?.lastPage ?? 1);
  readonly totalItems = computed(() => this.meta()?.total ?? 0);
  readonly isFirstPage = computed(() => this.currentPage() <= 1);
  readonly isLastPage = computed(() => this.currentPage() >= this.lastPage());

  readonly pageInfo = computed(() => {
    const m = this.meta();
    if (!m) return '';
    return `${m.from ?? 0}–${m.to ?? 0} de ${m.total}`;
  });

  ngOnInit(): void {
    this.fetchPage();
  }

  private fetchPage(): void {
    this.loading.set(true);
    this.error.set(null);

    this.service
      .index({
        page: this.currentPage(),
        perPage: 10,
        paginate: 1,
        search: this.searchInput() || undefined,
        orderByColumn: 'name',
        orderByDirection: 'asc',
      })
      .subscribe({
        next: (res) => {
          this.items.set(res.data);
          this.meta.set(res.meta);
          this.loading.set(false);
        },
        error: () => {
          this.error.set('Não foi possível carregar os centros de custo.');
          this.loading.set(false);
        },
      });
  }

  onSearch(): void {
    this.currentPage.set(1);
    this.fetchPage();
  }

  clearSearch(): void {
    this.searchInput.set('');
    this.currentPage.set(1);
    this.fetchPage();
  }

  goToPage(page: number): void {
    this.currentPage.set(page);
    this.fetchPage();
  }

  openCreate(): void {
    this.formData.set('create');
  }

  openEdit(item: CostCenter): void {
    this.formData.set(item);
  }

  closeForm(): void {
    this.formData.set(null);
  }

  onSaved(): void {
    this.closeForm();
    this.fetchPage();
  }

  openDelete(item: CostCenter): void {
    this.deletingItem.set(item);
    this.deleteError.set(null);
  }

  closeDelete(): void {
    this.deletingItem.set(null);
    this.deleteError.set(null);
  }

  confirmDelete(): void {
    const item = this.deletingItem();
    if (!item) return;

    this.deleteLoading.set(true);
    this.deleteError.set(null);

    this.service.destroy(item.id).subscribe({
      next: () => {
        this.deleteLoading.set(false);
        this.closeDelete();
        if (this.items().length === 1 && this.currentPage() > 1) {
          this.currentPage.update((p) => p - 1);
        }
        this.fetchPage();
      },
      error: (err) => {
        this.deleteError.set(err.error?.message ?? 'Erro ao remover. Tente novamente.');
        this.deleteLoading.set(false);
      },
    });
  }
}

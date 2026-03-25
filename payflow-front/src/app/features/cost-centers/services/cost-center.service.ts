import { inject, Injectable } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';
import { CostCenter } from '../models/cost-center';
import { CostCenterPayload } from '../models/cost-center-payload';
import { ApiResponse } from '../../../core/auth/models/auth.model';
import { PaginatedResponse } from '../../../shared/models/pagination.model';
import { environment } from '../../../../environments/environment';
import { transformToCamelCase } from '../../../shared/utils/transform-camel-case.util';

@Injectable({ providedIn: 'root' })
export class CostCenterService {
  private readonly http = inject(HttpClient);
  private readonly baseUrl = `${environment.apiUrl}/v1/cost-centers`;

  index(params?: Record<string, any>): Observable<PaginatedResponse<CostCenter>> {
    let httpParams = new HttpParams();
    if (params) {
      Object.entries(params).forEach(([key, value]) => {
        if (value !== undefined && value !== null && value !== '') {
          httpParams = httpParams.set(this.camelToSnakeCase(key), String(value));
        }
      });
    }
    return this.http
      .get<any>(this.baseUrl, {
        params: httpParams,
      })
      .pipe(map((response) => transformToCamelCase<PaginatedResponse<CostCenter>>(response)));
  }

  show(id: number): Observable<ApiResponse<CostCenter>> {
    return this.http
      .get<any>(`${this.baseUrl}/${id}`)
      .pipe(map((response) => transformToCamelCase<ApiResponse<CostCenter>>(response)));
  }

  store(payload: CostCenterPayload): Observable<ApiResponse<CostCenter>> {
    const apiPayload = this.transformPayloadToSnakeCase(payload);
    return this.http
      .post<any>(this.baseUrl, apiPayload)
      .pipe(map((response) => transformToCamelCase<ApiResponse<CostCenter>>(response)));
  }

  update(id: number, payload: Partial<CostCenterPayload>): Observable<ApiResponse<CostCenter>> {
    const apiPayload = this.transformPayloadToSnakeCase(payload);
    return this.http
      .put<any>(`${this.baseUrl}/${id}`, apiPayload)
      .pipe(map((response) => transformToCamelCase<ApiResponse<CostCenter>>(response)));
  }

  destroy(id: number): Observable<ApiResponse<null>> {
    return this.http.delete<ApiResponse<null>>(`${this.baseUrl}/${id}`);
  }

  private camelToSnakeCase(str: string): string {
    return str.replace(/[A-Z]/g, (letter) => `_${letter.toLowerCase()}`);
  }

  private transformPayloadToSnakeCase(payload: any): any {
    const transformed: any = {};
    for (const key in payload) {
      if (payload.hasOwnProperty(key)) {
        const snakeKey = this.camelToSnakeCase(key);
        transformed[snakeKey] = payload[key];
      }
    }
    return transformed;
  }
}

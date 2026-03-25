/**
 * Converte uma string de snake_case para camelCase
 * @param str String em snake_case
 * @returns String em camelCase
 */
export function snakeToCamelCase(str: string): string {
  return str.replace(/_([a-z])/g, (_, letter) => letter.toUpperCase());
}

/**
 * Converte as chaves de um objeto de snake_case para camelCase recursivamente
 * @param obj Objeto com chaves em snake_case
 * @returns Novo objeto com chaves em camelCase
 */
export function transformToCamelCase<T>(obj: any): T {
  if (obj === null || obj === undefined) {
    return obj;
  }

  // Se for um array, transforma cada elemento
  if (Array.isArray(obj)) {
    return obj.map((item) => transformToCamelCase(item)) as unknown as T;
  }

  // Se for um objeto
  if (typeof obj === 'object') {
    const transformed: any = {};
    for (const key in obj) {
      if (obj.hasOwnProperty(key)) {
        const camelKey = snakeToCamelCase(key);
        transformed[camelKey] = transformToCamelCase(obj[key]);
      }
    }
    return transformed as T;
  }

  // Se for um valor primitivo, retorna como está
  return obj as T;
}

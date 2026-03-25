/**
 * Formata uma data para o padrão brasileiro (DD/MM/YYYY)
 * @param dateString Data em formato ISO (YYYY-MM-DD ou completo)
 * @returns Data formatada em DD/MM/YYYY ou string vazia se inválida
 */
export function formatDateBR(dateString: string | null | undefined): string {
  if (!dateString) return '';

  try {
    const date = new Date(dateString);

    // Verifica se é uma data válida
    if (isNaN(date.getTime())) return '';

    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();

    return `${day}/${month}/${year}`;
  } catch {
    return '';
  }
}

/**
 * Formata uma data com hora para o padrão brasileiro (DD/MM/YYYY HH:mm)
 * @param dateString Data em formato ISO
 * @returns Data e hora formatadas em DD/MM/YYYY HH:mm ou string vazia se inválida
 */
export function formatDateTimeBR(dateString: string | null | undefined): string {
  if (!dateString) return '';

  try {
    const date = new Date(dateString);

    if (isNaN(date.getTime())) return '';

    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');

    return `${day}/${month}/${year} ${hours}:${minutes}`;
  } catch {
    return '';
  }
}

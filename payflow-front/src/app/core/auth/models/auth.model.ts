export interface User {
  id: number;
  name: string;
  email: string;
}

export interface LoginCredentials {
  email: string;
  password: string;
}

export interface RegisterCredentials {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
}

export interface AuthLoginResponse {
  status: string;
  message: string;
  data: {
    token: string;
    me: User;
  };
}

export interface ApiResponse<T> {
  status: string;
  message: string;
  data: T;
}

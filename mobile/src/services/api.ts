import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';
import Constants from 'expo-constants';

// MOCK_MODE = true permite testar a UI sem backend. O login aceita qualquer
// email/senha e a lista de agendamentos retorna dados fake. Coloque false
// quando os endpoints /api/* existirem no Laravel.
const MOCK_MODE = true;

const baseURL =
  (Constants.expoConfig?.extra?.apiBaseUrl as string | undefined) ??
  'http://10.0.2.2:8000';

export const api = axios.create({
  baseURL,
  headers: { Accept: 'application/json' },
  timeout: 15000,
});

api.interceptors.request.use(async (config) => {
  const token = await AsyncStorage.getItem('@timeplus/token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

const wait = (ms: number) => new Promise((r) => setTimeout(r, ms));

export async function login(email: string, password: string) {
  if (MOCK_MODE) {
    await wait(700);
    if (!email || !password) {
      throw { response: { data: { message: 'Preencha e-mail e senha.' } } };
    }
    await AsyncStorage.setItem('@timeplus/token', 'mock-token');
    return { token: 'mock-token', user: { name: email.split('@')[0] } };
  }

  const { data } = await api.post('/api/auth/login', { email, password });
  if (data?.token) {
    await AsyncStorage.setItem('@timeplus/token', data.token);
  }
  return data;
}

export async function fetchAppointments() {
  if (MOCK_MODE) {
    await wait(400);
    return [
      {
        id: 1,
        appointment_date: '30/04/2026',
        appointment_time: '14:00',
        specialist_name: 'Dra. Maria Silva',
        status: 'scheduled',
        room_code: 'sala-mock-1',
      },
      {
        id: 2,
        appointment_date: '02/05/2026',
        appointment_time: '10:30',
        specialist_name: 'Dr. João Pereira',
        status: 'scheduled',
        room_code: 'sala-mock-2',
      },
      {
        id: 3,
        appointment_date: '05/05/2026',
        appointment_time: '16:00',
        specialist_name: 'Dra. Verônica Costa',
        status: 'scheduled',
      },
    ];
  }

  const { data } = await api.get('/api/appointments/upcoming');
  return data?.data ?? [];
}

export async function logout() {
  await AsyncStorage.removeItem('@timeplus/token');
}

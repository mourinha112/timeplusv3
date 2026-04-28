import { useEffect, useState } from 'react';
import {
  ActivityIndicator,
  FlatList,
  RefreshControl,
  StyleSheet,
  Text,
  TouchableOpacity,
  View,
} from 'react-native';
import type { NativeStackScreenProps } from '@react-navigation/native-stack';
import type { RootStackParamList } from '../../App';
import { fetchAppointments, logout } from '../services/api';

type Props = NativeStackScreenProps<RootStackParamList, 'Appointments'>;

type Appointment = {
  id: number;
  appointment_date: string;
  appointment_time: string;
  specialist_name: string;
  status: string;
  room_code?: string;
};

export function AppointmentsScreen({ navigation }: Props) {
  const [items, setItems] = useState<Appointment[]>([]);
  const [loading, setLoading] = useState(true);

  const load = async () => {
    setLoading(true);
    try {
      const list = await fetchAppointments();
      setItems(list);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    load();
    navigation.setOptions({
      headerRight: () => (
        <TouchableOpacity
          onPress={async () => {
            await logout();
            navigation.replace('Login');
          }}
        >
          <Text style={{ color: '#fff', marginRight: 12 }}>Sair</Text>
        </TouchableOpacity>
      ),
    });
  }, [navigation]);

  if (loading && items.length === 0) {
    return (
      <View style={styles.center}>
        <ActivityIndicator size="large" color="#7464FF" />
      </View>
    );
  }

  return (
    <FlatList
      contentContainerStyle={styles.list}
      refreshControl={<RefreshControl refreshing={loading} onRefresh={load} />}
      data={items}
      keyExtractor={(it) => String(it.id)}
      renderItem={({ item }) => (
        <View style={styles.row}>
          <Text style={styles.specialist}>{item.specialist_name}</Text>
          <Text style={styles.date}>
            {item.appointment_date} {item.appointment_time}
          </Text>
          <Text style={styles.status}>{item.status}</Text>
          {item.room_code ? (
            <TouchableOpacity
              style={styles.button}
              onPress={() =>
                navigation.navigate('VideoCall', {
                  roomCode: item.room_code as string,
                  userName: 'Paciente',
                })
              }
            >
              <Text style={styles.buttonText}>Entrar no atendimento</Text>
            </TouchableOpacity>
          ) : null}
        </View>
      )}
      ListEmptyComponent={
        <View style={styles.center}>
          <Text style={{ color: '#6b7280' }}>Nenhuma sessão agendada.</Text>
        </View>
      }
    />
  );
}

const styles = StyleSheet.create({
  center: { flex: 1, alignItems: 'center', justifyContent: 'center', padding: 20 },
  list: { padding: 16 },
  row: {
    backgroundColor: '#fff',
    borderRadius: 12,
    padding: 16,
    marginBottom: 12,
    shadowColor: '#000',
    shadowOpacity: 0.05,
    shadowRadius: 4,
    elevation: 2,
  },
  specialist: { fontSize: 16, fontWeight: '600', color: '#111827' },
  date: { fontSize: 14, color: '#6b7280', marginTop: 4 },
  status: { fontSize: 12, color: '#7464FF', marginTop: 4 },
  button: {
    backgroundColor: '#7464FF',
    borderRadius: 8,
    padding: 10,
    alignItems: 'center',
    marginTop: 12,
  },
  buttonText: { color: '#fff', fontWeight: '600' },
});

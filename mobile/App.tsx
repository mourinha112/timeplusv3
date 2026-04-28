import { NavigationContainer } from '@react-navigation/native';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { StatusBar } from 'expo-status-bar';
import { LoginScreen } from './src/screens/LoginScreen';
import { AppointmentsScreen } from './src/screens/AppointmentsScreen';
import { VideoCallScreen } from './src/screens/VideoCallScreen';

export type RootStackParamList = {
  Login: undefined;
  Appointments: undefined;
  VideoCall: { roomCode: string; userName: string };
};

const Stack = createNativeStackNavigator<RootStackParamList>();

export default function App() {
  return (
    <NavigationContainer>
      <StatusBar style="auto" />
      <Stack.Navigator
        initialRouteName="Login"
        screenOptions={{
          headerStyle: { backgroundColor: '#7464FF' },
          headerTintColor: '#fff',
        }}
      >
        <Stack.Screen
          name="Login"
          component={LoginScreen}
          options={{ title: 'TimePlus', headerShown: false }}
        />
        <Stack.Screen
          name="Appointments"
          component={AppointmentsScreen}
          options={{ title: 'Meus Agendamentos' }}
        />
        <Stack.Screen
          name="VideoCall"
          component={VideoCallScreen}
          options={{ title: 'Atendimento' }}
        />
      </Stack.Navigator>
    </NavigationContainer>
  );
}

import { useEffect } from 'react';
import { StyleSheet, Text, View } from 'react-native';
import Constants from 'expo-constants';
import type { NativeStackScreenProps } from '@react-navigation/native-stack';
import type { RootStackParamList } from '../../App';

// IMPORTANTE: a integração com o Jitsi exige Expo dev client OU bare workflow.
// O pacote `react-native-jitsi-meet-sdk` requer linkagem nativa (não funciona no Expo Go).
//
// Após `npm install`:
//   npx expo prebuild
//   npx expo run:android   (ou run:ios)
//
// Fluxo recomendado:
// 1. Importar JitsiMeet condicionalmente (try/catch porque o módulo nativo
//    só existe no build dev/prod, nunca no Expo Go).
// 2. Chamar JitsiMeet.launchJitsiMeetView({ room, userInfo: { displayName } }).

type Props = NativeStackScreenProps<RootStackParamList, 'VideoCall'>;

export function VideoCallScreen({ route, navigation }: Props) {
  const { roomCode, userName } = route.params;
  const jitsiServer =
    (Constants.expoConfig?.extra?.jitsiServer as string | undefined) ?? 'https://meet.jit.si';

  useEffect(() => {
    let cancelled = false;
    (async () => {
      try {
        // Carregamento dinâmico para não quebrar no Expo Go.
        const Jitsi = await import('react-native-jitsi-meet-sdk').catch(() => null);
        if (cancelled || !Jitsi?.default) {
          return;
        }
        Jitsi.default.launchJitsiMeetView({
          room: `${jitsiServer}/${roomCode}`,
          userInfo: { displayName: userName },
          audioMuted: false,
          videoMuted: false,
        });
      } catch (err) {
        // Falha silenciosa para o Expo Go — UI mostra mensagem abaixo.
        // eslint-disable-next-line no-console
        console.warn('Jitsi não disponível neste runtime:', err);
      }
    })();

    return () => {
      cancelled = true;
    };
  }, [roomCode, userName, jitsiServer]);

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Atendimento {roomCode}</Text>
      <Text style={styles.text}>
        Caso a tela do Jitsi não abra automaticamente, este build não tem o módulo nativo
        instalado. Use `npx expo prebuild` + `expo run:android/ios` para gerar um build de
        desenvolvimento (não funciona no Expo Go).
      </Text>
      <Text style={styles.serverText}>Servidor Jitsi: {jitsiServer}</Text>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, padding: 24, backgroundColor: '#000' },
  title: { color: '#fff', fontSize: 20, fontWeight: '700', marginBottom: 12 },
  text: { color: '#d1d5db', lineHeight: 20 },
  serverText: { color: '#7464FF', marginTop: 16, fontSize: 12 },
});

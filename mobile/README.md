# TimePlus Mobile (React Native + Expo)

App mobile nativo do TimePlus. **Este é um scaffold inicial** — login, listagem de agendamentos e
um placeholder para o atendimento via Jitsi. Não é um app pronto para publicar; é o ponto de partida.

## Requisitos

- Node.js 20+
- Expo CLI: `npm install -g expo-cli`
- JDK 17 e Android SDK (para builds Android)
- Xcode (para builds iOS)

## Setup

```bash
cd mobile
npm install
```

## Configurar a API e o Jitsi

Edite `app.json` na chave `expo.extra`:

```json
{
  "apiBaseUrl": "https://timeplus.exemplo.com",
  "jitsiServer": "https://meet.jit.si"
}
```

> Para emulador Android local apontando para `php artisan serve`, use `http://10.0.2.2:8000`.

## Criar endpoints da API

O backend Laravel **ainda não tem** as rotas API (`/api/auth/login`, `/api/appointments/upcoming`)
que este app consome. Crie um `routes/api.php` com:

- `POST /api/auth/login` → autentica via `auth:user` e retorna `{ token: ..., user: ... }` (Sanctum).
- `GET /api/appointments/upcoming` (auth Sanctum) → próximos agendamentos do usuário logado, formato:
  ```json
  {
    "data": [
      {
        "id": 12,
        "appointment_date": "30/04/2026",
        "appointment_time": "14:00",
        "specialist_name": "Dra. Maria",
        "status": "scheduled",
        "room_code": "abc123"
      }
    ]
  }
  ```

Também é preciso `composer require laravel/sanctum` + migrations + middleware no kernel.
Esse trabalho não é parte deste scaffold.

## Build de desenvolvimento (necessário para Jitsi)

O Jitsi exige módulo nativo — **não roda no Expo Go**. Gere um dev client:

```bash
npx expo prebuild
npx expo run:android   # ou run:ios
```

## Estrutura

```
mobile/
├── App.tsx                       # navegação raiz
├── app.json                      # config Expo
├── package.json
├── tsconfig.json
└── src/
    ├── screens/
    │   ├── LoginScreen.tsx       # tela de login
    │   ├── AppointmentsScreen.tsx# lista de sessões
    │   └── VideoCallScreen.tsx   # placeholder Jitsi
    └── services/
        └── api.ts                # cliente axios + token storage
```

## Próximos passos (não inclusos neste scaffold)

1. Implementar endpoints API no Laravel (login com Sanctum, lista de sessões, sala).
2. Adicionar tela de cadastro / recuperação de senha.
3. Implementar push notifications (`expo-notifications`).
4. Implementar fluxo de pagamento (PIX/Cartão) — provavelmente via WebView abrindo o checkout web,
   já que o Asaas não tem SDK nativo oficial.
5. Versão do especialista (atendimento, gestão de agenda).
6. Pipeline de release (EAS Build + EAS Submit).

## Stack

- Expo SDK 51 + React Native 0.74
- React Navigation (native stack)
- axios + AsyncStorage
- react-native-jitsi-meet-sdk (atendimento)

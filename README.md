<div align="center">
  <!-- Placeholder for an animated project logo or GIF -->
  <img src="https://via.placeholder.com/800x200/050a1f/00ffcc?text=+++SwarAI+++Audio+++Engine+++" alt="SwarAI Banner">

  <h1>🎙️ SwarAI - Audio Translation Engine</h1>
  <p><b>Enterprise-grade, real-time English-to-Hindi speech translation pipeline powered by Gemini AI.</b></p>
  
  <p>
    <img src="https://img.shields.io/badge/Backend-Go_1.21+-00ADD8?style=for-the-badge&logo=go" alt="Go">
    <img src="https://img.shields.io/badge/Frontend-PHP_8.1+-777BB4?style=for-the-badge&logo=php" alt="PHP">
    <img src="https://img.shields.io/badge/AI-Gemini_3.7_Flash-FF6F00?style=for-the-badge&logo=google" alt="Gemini">
    <img src="https://img.shields.io/badge/Status-Telemetry_Online-00ffcc?style=for-the-badge" alt="Status">
  </p>
</div>

---

## 📡 System Telemetry & Architecture

The system is composed of two primary, decoupled components that communicate seamlessly. Here is the data flow matrix:

```mermaid
sequenceDiagram
    participant U as 👤 User (Microphone)
    participant H as 🖥️ Enterprise HUD (PHP/JS)
    participant G as ⚙️ Go Engine
    participant AI as 🧠 Gemini 3.7 Flash

    U->>H: Speaks in English
    Note over H: Base64 Encoding &<br/>Acoustic Profiling
    H->>G: POST /api/ingest (Base64 Audio)
    G->>AI: genai SDK Prompt + Audio
    AI-->>G: Hindi Translated Text Response
    G-->>H: 200 OK (JSON Payload)
    H->>U: Displays Hindi Text on Terminal

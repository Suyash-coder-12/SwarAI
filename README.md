<div align="center">
  <!-- Placeholder for an animated project logo or GIF -->
  <img src="https://via.placeholder.com/600x200/000000/00ffcc?text=++SwarAI+Audio+Engine++" alt="SwarAI Banner">

  <h1>🎙️ SwarAI - Audio Translation Engine</h1>
  <p><b>Enterprise-grade, real-time English-to-Hindi speech translation pipeline powered by Gemini AI.</b></p>
  
  <p>
    <img src="https://img.shields.io/badge/Backend-Go_1.21+-00ADD8?style=for-the-badge&logo=go" alt="Go">
    <img src="https://img.shields.io/badge/Frontend-PHP_8.1+-777BB4?style=for-the-badge&logo=php" alt="PHP">
    <img src="https://img.shields.io/badge/AI-Gemini_3.7_Flash-FF6F00?style=for-the-badge&logo=google" alt="Gemini">
    <img src="https://img.shields.io/badge/License-MIT-success?style=for-the-badge" alt="License">
  </p>
</div>

## 🌌 Architecture

The system is composed of two primary, decoupled components:

1. **Go Engine (`go-engine/`)**: A fast, lightweight backend service written in Go. It exposes RESTful APIs to ingest base64-encoded audio streams, processes them using the `google.golang.org/genai` SDK, and translates English audio to Hindi text in milliseconds.
2. **Enterprise Interface (`swarai-enterprise/`)**: A highly polished, responsive web application (PHP/Vanilla JS/CSS) serving as the command center. It features an interactive UI—complete with a HUD overview, acoustic profiling, NMT matrix, and system telemetry—to visualize the translation pipeline in real time.

## ✨ Key Features

* **⚡ Real-Time Translation**: Seamless English-to-Hindi translation using Google's state-of-the-art `gemini-3.7-flash` model.
* **🎥 Sci-Fi HUD UI**: A visually stunning dashboard featuring live telemetry, parametric frequency visualizers, and system health metrics.
* **🚀 Go-Powered Backend**: A robust HTTP server handling CORS, concurrent audio decoding, and low-latency API interactions.
* **🌊 Audio Processing Pipeline**: Efficiently handles microphone ingestion, base64 encoding/decoding, and prompt-engineered interactions.

## 🛠️ Prerequisites

* **Go** (`v1.21` or higher)
* **PHP** (`v8.1` or higher)
* **Composer** (for PHP dependencies)
* A valid **Google Gemini API Key** configured in your environment.

## 🚀 Installation & Setup

### 1. Initialize the Go Engine
Navigate to the engine directory, download dependencies, and ignite the server:

```bash
cd go-engine
go mod download
go run cmd/server/main.go

# SwarAI - Audio Translation Engine

SwarAI is an advanced, enterprise-grade audio processing and translation pipeline. It features a high-performance Go backend powered by the Gemini AI model to perform real-time English-to-Hindi speech translation, coupled with a futuristic, sci-fi HUD web interface built in PHP and vanilla JavaScript/CSS.

## Architecture

The system is composed of two primary components:

1. **Go Engine (`go-engine/`)**: A fast and lightweight backend service written in Go. It exposes RESTful APIs to ingest base64-encoded audio streams, processes them using the `google.golang.org/genai` SDK, and translates the English audio to Hindi text via the `gemini-3.7-flash` model.
2. **Enterprise Interface (`swarai-enterprise/`)**: A highly polished, responsive web application (PHP/JS/CSS) that serves as the command center. It features an interactive UI (HUD overview, acoustic profiling, NMT matrix, and system telemetry) to visualize the audio ingestion and translation process.

## Key Features

- **Real-Time Translation**: Seamless English-to-Hindi translation using Google's state-of-the-art Gemini model.
- **Sci-Fi HUD UI**: A visually stunning dashboard featuring live telemetry, parametric frequency visualizers, and system health metrics.
- **Go-Powered Backend**: A robust HTTP server handling CORS, audio decoding, and API interactions efficiently.
- **Audio Processing Pipeline**: Handles audio ingestion, base64 decoding, and prompt-engineered interactions with the Gemini API.

## Prerequisites

- **Go** (1.21 or higher) for the engine.
- **PHP** (8.1 or higher) for the enterprise frontend.
- **Composer** for PHP dependencies.
- A valid **Gemini API Key** configured in your environment.

## Installation & Setup

### 1. Setup the Go Engine

Navigate to the `go-engine` directory and run the server:

```bash
cd go-engine
go mod download
go run cmd/server/main.go
```
The Go engine will start listening on `localhost`.

### 2. Setup the Enterprise Interface

Navigate to the `swarai-enterprise` directory, install dependencies, and start the PHP development server:

```bash
cd swarai-enterprise
composer install
php -S localhost:8000 -t public
```
The interface will be available at `No Live Link Available, We are Working on it Locally`.

## Usage

1. Ensure both the Go engine and the PHP frontend are running.
2. working on it
4. Use the interface to initiate an audio ingestion sequence. The frontend will capture the audio and send it to the Go backend.
5. The Go backend processes the audio, interfaces with the Gemini API, and returns the translated Hindi text, which is then displayed on the UI terminal.

## License

This project is licensed under the MIT License.

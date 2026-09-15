<div align="center">
  <img src="https://via.placeholder.com/1000x200/ffffff/333333?text=SwarAI+Audio+Translation+Pipeline" alt="SwarAI Banner">

  <h1>SwarAI: Real-Time Audio Translation Pipeline</h1>
  <p><b>An enterprise-grade, low-latency speech-to-text translation engine powered by Go and Gemini AI.</b></p>
  
  <p>
    <img src="https://img.shields.io/badge/Backend-Go_1.21+-00ADD8?style=for-the-badge&logo=go" alt="Go">
    <img src="https://img.shields.io/badge/Frontend-PHP_8.1+-777BB4?style=for-the-badge&logo=php" alt="PHP">
    <img src="https://img.shields.io/badge/LLM-Gemini_3.7_Flash-FF6F00?style=for-the-badge&logo=google" alt="Gemini">
    <img src="https://img.shields.io/badge/Build-Passing-brightgreen?style=for-the-badge" alt="Build">
    <img src="https://img.shields.io/badge/License-MIT-blue?style=for-the-badge" alt="License">
  </p>
</div>

---

## 📑 Table of Contents
1. [Project Overview](#-project-overview)
2. [System Architecture](#-system-architecture)
3. [Core Capabilities](#-core-capabilities)
4. [Technology Stack](#-technology-stack)
5. [Getting Started (Installation)](#-getting-started-installation)
6. [API Documentation](#-api-documentation)
7. [Directory Structure](#-directory-structure)
8. [Roadmap](#-roadmap)

---

## 🚀 Project Overview

**SwarAI** is a robust, real-time audio processing pipeline designed to seamlessly translate English speech into Hindi text. Built with scalability in mind, it leverages a highly concurrent **Go (Golang)** backend to process base64-encoded audio streams and interfaces with Google's **Gemini 3.7 Flash** model for highly accurate, context-aware translation. The presentation layer is managed by a lightweight, interactive **PHP and Vanilla JavaScript** dashboard that provides real-time acoustic feedback and processing metrics.

---

## 📐 System Architecture

The system follows a microservice-oriented architecture, strictly decoupling the client interface from the heavy processing engine.

### Comprehensive Data Flow & System Map
```mermaid
flowchart TB
    %% Styling
    classDef client fill:#f8f9fa,stroke:#ced4da,stroke-width:2px,color:#212529;
    classDef frontend fill:#e3f2fd,stroke:#90caf9,stroke-width:2px,color:#0d47a1;
    classDef backend fill:#e0f7fa,stroke:#4dd0e1,stroke-width:2px,color:#006064;
    classDef external fill:#fff3e0,stroke:#ffb74d,stroke-width:2px,color:#e65100;

    %% Client Layer
    subgraph Client_Layer ["💻 Client Presentation Layer (Browser)"]
        direction TB
        MIC([Microphone Input])
        WEB_AUDIO[Web Audio API]
        BLOB[Audio Blob Encapsulation]
        B64[Base64 Stream Encoder]
        DOM[DOM / UI Renderer]
        
        MIC --> WEB_AUDIO
        WEB_AUDIO --> BLOB
        BLOB --> B64
    end

    %% Application Layer (Frontend Server)
    subgraph App_Layer ["🌐 Application Layer (PHP - Port: 8000)"]
        ASSETS[Static Assets Serving]
        ROUTER[Frontend Router]
    end

    %% Service Layer (Go Backend)
    subgraph Backend_Layer ["⚙️ Core Processing Engine (Go - Port: 8080)"]
        direction TB
        API_GW{API Gateway / MUX}
        WORKER_POOL{Goroutine Worker Pool}
        
        subgraph Pipeline ["Processing Pipeline"]
            DECODER[Base64 Decoder]
            PAYLOAD[Request Payload Builder]
        end
        
        API_GW -->|HTTP POST| WORKER_POOL
        WORKER_POOL --> DECODER
        DECODER --> PAYLOAD
    end

    %% Third-Party Services
    subgraph AI_Layer ["🧠 Cloud AI Services"]
        direction TB
        GENAI_SDK[Google GenAI SDK]
        GEMINI([Gemini 3.7 Flash Model])
        
        GENAI_SDK --> GEMINI
    end

    %% Inter-Layer Communications
    B64 -->|REST API Call| API_GW
    PAYLOAD -->|gRPC / HTTP2| GENAI_SDK
    GEMINI -->|JSON Response - Hindi Text| WORKER_POOL
    WORKER_POOL -->|HTTP 200 Response| DOM
    
    %% Relationships
    Client_Layer -.->|Loads App| App_Layer
    App_Layer -.-> ASSETS

    %% Apply Classes
    class Client_Layer,MIC,WEB_AUDIO,BLOB,B64,DOM client;
    class App_Layer,ASSETS,ROUTER frontend;
    class Backend_Layer,API_GW,WORKER_POOL,DECODER,PAYLOAD backend;
    class AI_Layer,GENAI_SDK,GEMINI external;

<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Core/Application.php';

if ($_SERVER['REQUEST_URI'] === '/api/health') {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'online']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SwarAI - Engine Interface</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&family=Space+Grotesk:wght@400;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  
  <link rel="stylesheet" href="/css/variables.css">
  <link rel="stylesheet" href="/css/base.css">
  <link rel="stylesheet" href="/css/layout.css">
  <link rel="stylesheet" href="/css/components/components.css">
</head>
<body>
  <div class="app-container">
    
    <!-- Sidebar Navigation -->
    <aside class="app-sidebar">
      <div style="padding: 0 32px 32px; margin-bottom: 24px; border-bottom: 1px solid var(--bg-hud-border);">
        <h2 style="color: var(--text-bright); font-family: var(--font-display); letter-spacing: 4px; display: flex; flex-direction: column; gap: 4px; font-weight: 700;">
          SWARAI
          <span style="font-size: 10px; color: var(--neon-cyan); letter-spacing: 2px;">CORE_SYSTEM_V2.5</span>
        </h2>
      </div>
      <nav id="sidebar-nav">
        <div class="nav-item active" data-target="view-hud">
          <span class="nav-item-icon">
            <svg viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
          </span> 
          HUD OVERVIEW
        </div>
        <div class="nav-item" data-target="view-acoustics">
          <span class="nav-item-icon">
            <svg viewBox="0 0 24 24"><path d="M12 14c1.66 0 2.99-1.34 2.99-3L15 5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3zm5.3-3c0 3-2.54 5.1-5.3 5.1S6.7 14 6.7 11H5c0 3.41 2.72 6.23 6 6.72V21h2v-3.28c3.28-.48 6-3.3 6-6.72h-1.7z"/></svg>
          </span> 
          ACOUSTIC PROFILING
        </div>
        <div class="nav-item" data-target="view-matrix">
          <span class="nav-item-icon">
            <svg viewBox="0 0 24 24"><path d="M21 4H3v16h18V4zm-10 7H9.5v-.5h-2v3h2V13H11v1h-3c-.55 0-1-.45-1-1v-4c0-.55.45-1 1-1h3c.55 0 1 .45 1 1v1zm7 0h-1.5v-.5h-2v3h2V13H18v1h-3c-.55 0-1-.45-1-1v-4c0-.55.45-1 1-1h3c.55 0 1 .45 1 1v1z"/></svg>
          </span> 
          NMT MATRIX
        </div>
        <div class="nav-item" data-target="view-telemetry">
          <span class="nav-item-icon">
            <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/></svg>
          </span> 
          SYSTEM TELEMETRY
        </div>
      </nav>
      <div style="margin-top: auto; padding: 24px;">
        <div style="font-family: var(--font-data); font-size: 11px; color: var(--text-muted); display: flex; justify-content: space-between;">
          <span>BUILD:</span> <span>849.2-REL</span>
        </div>
        <div style="font-family: var(--font-data); font-size: 11px; color: var(--text-muted); display: flex; justify-content: space-between; margin-top: 4px;">
          <span>ENCRYPTION:</span> <span style="color: var(--status-green);">AES-256</span>
        </div>
      </div>
    </aside>

    <!-- Top Header -->
    <header class="app-header">
      <div style="font-family: var(--font-data); color: var(--text-muted); font-size: 12px; letter-spacing: 2px;">
        CLUSTER: <span style="color: var(--neon-cyan); font-weight: bold;">SYS-ALPHA-01</span>
      </div>
      <div style="display: flex; gap: 24px; align-items: center;">
        <div style="display: flex; align-items: center; gap: 8px; font-family: var(--font-data); font-size: 11px;">
          <span style="color: var(--text-muted);">UPLINK STATUS:</span>
          <div style="width: 8px; height: 8px; background: var(--status-green); border-radius: 50%; animation: pulse-dot 2s infinite;"></div>
          <span style="color: var(--status-green); font-weight: bold;">SECURE</span>
        </div>
        <button class="btn btn-outline" id="btn-export">Export Trace</button>
        <button class="btn btn-primary" id="btn-process">INITIATE INGESTION</button>
      </div>
    </header>

    <!-- Main Content Area -->
    <main class="app-main">
      
      <!-- HUD VIEW -->
      <div id="view-hud" class="view-section active">
        <div class="glass-panel" style="margin-bottom: 8px;">
          <div class="widget-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="var(--neon-cyan)" style="margin-right: 8px;"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
            Parametric Frequency Matrix
          </div>
          <div class="visualizer-container" id="audio-visualizer">
            <!-- Visualizer bars injected via JS -->
          </div>
        </div>

        <div class="widget-grid">
          <div class="glass-panel">
            <div class="widget-title">Stream Telemetry Details</div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-family: var(--font-data); font-size: 13px; color: var(--text-muted);">
              <div>SAMPLE_RATE</div><div style="color: var(--text-bright); text-align: right; font-weight: bold;">48.0 KHz</div>
              <div>BIT_DEPTH</div><div style="color: var(--text-bright); text-align: right; font-weight: bold;">24-BIT</div>
              <div>CHANNELS</div><div style="color: var(--text-bright); text-align: right; font-weight: bold;">STEREO</div>
              <div>CODEC</div><div style="color: var(--neon-cyan); text-align: right; font-weight: bold;">RAW/PCM</div>
              <div>IN_BUFFER</div><div style="color: var(--text-bright); text-align: right; font-weight: bold;">12.4 MB/s</div>
              <div>LATENCY</div><div style="color: var(--status-green); text-align: right; font-weight: bold;">8 MS</div>
            </div>
          </div>

          <div class="glass-panel">
            <div class="widget-title">Neural Engine Pipeline</div>
            <div style="display: flex; flex-direction: column; gap: 16px; font-family: var(--font-data); font-size: 13px;">
              <div style="display: flex; justify-content: space-between; align-items: center; color: var(--text-muted);">
                <span>SOURCE NODE</span>
                <span style="color: var(--text-bright); background: rgba(0,0,0,0.02); padding: 4px 12px; border-radius: 4px; border: 1px solid var(--bg-hud-border);">ENG-US (Wav2Vec)</span>
              </div>
              <div style="display: flex; justify-content: space-between; align-items: center; color: var(--text-muted);">
                <span>TARGET NODE</span>
                <span style="color: var(--text-bright); background: rgba(0,0,0,0.02); padding: 4px 12px; border-radius: 4px; border: 1px solid var(--bg-hud-border);">HIN-IN (FastSpeech)</span>
              </div>
              <div style="display: flex; justify-content: space-between; align-items: center; color: var(--text-muted);">
                <span>CER / WER</span>
                <span style="color: var(--text-bright);">1.2% / 3.4%</span>
              </div>
              <div style="display: flex; justify-content: space-between; align-items: center; color: var(--text-muted); border-top: 1px solid var(--bg-hud-border); padding-top: 12px;">
                <span>CONFIDENCE SCORE</span>
                <span style="color: var(--neon-purple); font-weight: bold; font-size: 18px;">99.2%</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ACOUSTICS VIEW -->
      <div id="view-acoustics" class="view-section">
        <div class="glass-panel">
          <div class="widget-title">Acoustic Processing Profile</div>
          <p style="color: var(--text-muted); margin-bottom: 24px; font-size: 14px;">Advanced AI-driven control over environmental noise gating, dynamic compression, and multi-band vocal isolation.</p>
          
          <div style="display: flex; flex-direction: column; gap: 24px;">
            <div>
              <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-family: var(--font-data);">
                <span style="font-weight: 600; color: var(--text-bright);">AI Noise Suppression Level</span>
                <span style="color: var(--neon-cyan); font-weight: bold;">85% (Aggressive)</span>
              </div>
              <div style="height: 8px; background: rgba(0,0,0,0.05); border-radius: 4px; overflow: hidden;">
                <div style="height: 100%; width: 85%; background: var(--neon-cyan);"></div>
              </div>
            </div>
            
            <div>
              <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-family: var(--font-data);">
                <span style="font-weight: 600; color: var(--text-bright);">Vocal Isolation Factor</span>
                <span style="color: var(--neon-purple); font-weight: bold;">92% (High-Q)</span>
              </div>
              <div style="height: 8px; background: rgba(0,0,0,0.05); border-radius: 4px; overflow: hidden;">
                <div style="height: 100%; width: 92%; background: var(--neon-purple);"></div>
              </div>
            </div>

            <div>
              <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-family: var(--font-data);">
                <span style="font-weight: 600; color: var(--text-bright);">De-Essing & Sibilance</span>
                <span style="color: var(--status-warn); font-weight: bold;">45% (Nominal)</span>
              </div>
              <div style="height: 8px; background: rgba(0,0,0,0.05); border-radius: 4px; overflow: hidden;">
                <div style="height: 100%; width: 45%; background: var(--status-warn);"></div>
              </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--bg-hud-border);">
              <button class="btn btn-outline" style="width: 100%;">RUN CALIBRATION SEQUENCE</button>
              <button class="btn btn-primary" style="width: 100%;">DEPLOY TO MATRIX</button>
            </div>
          </div>
        </div>
      </div>

      <!-- MATRIX VIEW -->
      <div id="view-matrix" class="view-section">
        <div class="glass-panel" style="margin-bottom: 16px;">
          <div class="widget-title">Neural Machine Translation Pipeline</div>
          <div style="display: flex; justify-content: space-between; align-items: center; padding: 32px 24px; background: rgba(0,0,0,0.02); border-radius: 8px; border: 1px dashed rgba(0,0,0,0.05);">
            <div style="text-align: center; width: 160px;">
              <div style="font-size: 28px; font-weight: 700; color: var(--text-bright); font-family: var(--font-display);">ENG-US</div>
              <div style="color: var(--neon-purple); font-size: 11px; font-family: var(--font-data); margin-top: 4px; font-weight: bold;">SOURCE TENSOR</div>
            </div>
            <div style="flex: 1; text-align: center; position: relative;">
              <div style="height: 2px; background: linear-gradient(90deg, transparent, var(--neon-cyan), transparent); width: 100%; position: absolute; top: 50%; z-index: 0;"></div>
              <span style="background: var(--bg-hud-solid); padding: 6px 16px; border-radius: 20px; border: 1px solid var(--neon-cyan); position: relative; z-index: 1; font-weight: bold; color: var(--neon-cyan); font-size: 12px; font-family: var(--font-data); letter-spacing: 1px;">TRANSFORMER CORE</span>
            </div>
            <div style="text-align: center; width: 160px;">
              <div style="font-size: 28px; font-weight: 700; color: var(--text-bright); font-family: var(--font-display);">HIN-IN</div>
              <div style="color: var(--neon-cyan); font-size: 11px; font-family: var(--font-data); margin-top: 4px; font-weight: bold;">TARGET TENSOR</div>
            </div>
          </div>
        </div>
        
        <div class="widget-grid">
          <div class="glass-panel">
            <div class="widget-title">Contextual Vocabulary Subsystem</div>
            <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid var(--bg-hud-border); font-family: var(--font-data); font-size: 13px;">
              <span style="color: var(--text-bright);">Enterprise Jargon Filter</span>
              <span style="color: var(--status-green); font-weight: bold;">ENABLED</span>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid var(--bg-hud-border); font-family: var(--font-data); font-size: 13px;">
              <span style="color: var(--text-bright);">Contextual Memory Buffer</span>
              <span style="color: var(--neon-purple); font-weight: bold;">ACTIVE (256K TOKENS)</span>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 12px 0; font-family: var(--font-data); font-size: 13px;">
              <span style="color: var(--text-bright);">Dialect Adaptation Grid</span>
              <span style="color: var(--neon-cyan); font-weight: bold;">AUTO-DETECTING</span>
            </div>
          </div>
        </div>
      </div>

      <!-- TELEMETRY VIEW -->
      <div id="view-telemetry" class="view-section">
        <div class="glass-panel">
          <div class="widget-title">Cluster Health & Resource Load</div>
          <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 32px;">
            <div style="text-align: center; padding: 24px; border: 1px solid rgba(0,0,0,0.02); border-radius: 12px; background: rgba(0,0,0,0.02);">
              <div style="font-size: 36px; font-weight: 700; color: var(--text-bright); font-family: var(--font-display);">24%</div>
              <div style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; font-family: var(--font-data); letter-spacing: 1px; margin-top: 8px;">GPU Usage (A100)</div>
              <div style="height: 4px; background: rgba(0,0,0,0.1); border-radius: 2px; margin-top: 12px; overflow: hidden;"><div style="width: 24%; height: 100%; background: var(--status-green);"></div></div>
            </div>
            <div style="text-align: center; padding: 24px; border: 1px solid rgba(0,0,0,0.02); border-radius: 12px; background: rgba(0,0,0,0.02);">
              <div style="font-size: 36px; font-weight: 700; color: var(--text-bright); font-family: var(--font-display);">18.2<span style="font-size: 18px; color: var(--text-muted);">GB</span></div>
              <div style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; font-family: var(--font-data); letter-spacing: 1px; margin-top: 8px;">VRAM Allocated</div>
              <div style="height: 4px; background: rgba(0,0,0,0.1); border-radius: 2px; margin-top: 12px; overflow: hidden;"><div style="width: 45%; height: 100%; background: var(--neon-purple);"></div></div>
            </div>
            <div style="text-align: center; padding: 24px; border: 1px solid rgba(0,0,0,0.02); border-radius: 12px; background: rgba(0,0,0,0.02);">
              <div style="font-size: 36px; font-weight: 700; color: var(--status-green); font-family: var(--font-display);">8<span style="font-size: 18px; color: var(--status-green);">ms</span></div>
              <div style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; font-family: var(--font-data); letter-spacing: 1px; margin-top: 8px;">Inference Latency</div>
              <div style="height: 4px; background: rgba(0,0,0,0.1); border-radius: 2px; margin-top: 12px; overflow: hidden;"><div style="width: 10%; height: 100%; background: var(--status-green);"></div></div>
            </div>
          </div>
          
          <div class="widget-title">Active Inference Nodes</div>
          <div style="overflow-x: auto;">
            <table style="width: 100%; text-align: left; border-collapse: separate; border-spacing: 0; font-family: var(--font-data); font-size: 13px;">
              <thead>
                <tr>
                  <th style="padding: 12px; border-bottom: 1px solid var(--bg-hud-border); color: var(--text-muted); font-weight: normal; text-transform: uppercase; letter-spacing: 1px;">Node Identifier</th>
                  <th style="padding: 12px; border-bottom: 1px solid var(--bg-hud-border); color: var(--text-muted); font-weight: normal; text-transform: uppercase; letter-spacing: 1px;">Task Assignment</th>
                  <th style="padding: 12px; border-bottom: 1px solid var(--bg-hud-border); color: var(--text-muted); font-weight: normal; text-transform: uppercase; letter-spacing: 1px;">Status</th>
                  <th style="padding: 12px; border-bottom: 1px solid var(--bg-hud-border); color: var(--text-muted); font-weight: normal; text-transform: uppercase; letter-spacing: 1px; text-align: right;">Uptime</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td style="padding: 16px 12px; color: var(--text-bright); border-bottom: 1px solid rgba(0,0,0,0.02);">sys-node-alpha</td>
                  <td style="padding: 16px 12px; border-bottom: 1px solid rgba(0,0,0,0.02); color: var(--text-muted);">Audio Ingestion / VAD</td>
                  <td style="padding: 16px 12px; border-bottom: 1px solid rgba(0,0,0,0.02);">
                    <span style="display: inline-flex; align-items: center; gap: 6px; background: rgba(16,185,129,0.1); color: var(--status-green); padding: 4px 8px; border-radius: 4px; border: 1px solid rgba(16,185,129,0.2);">
                      <span style="width: 6px; height: 6px; background: var(--status-green); border-radius: 50%;"></span> OPTIMAL
                    </span>
                  </td>
                  <td style="padding: 16px 12px; border-bottom: 1px solid rgba(0,0,0,0.02); text-align: right; color: var(--text-bright);">12h 43m</td>
                </tr>
                <tr>
                  <td style="padding: 16px 12px; color: var(--text-bright); border-bottom: 1px solid rgba(0,0,0,0.02);">sys-node-beta</td>
                  <td style="padding: 16px 12px; border-bottom: 1px solid rgba(0,0,0,0.02); color: var(--text-muted);">Transformer Model (NMT)</td>
                  <td style="padding: 16px 12px; border-bottom: 1px solid rgba(0,0,0,0.02);">
                    <span style="display: inline-flex; align-items: center; gap: 6px; background: rgba(16,185,129,0.1); color: var(--status-green); padding: 4px 8px; border-radius: 4px; border: 1px solid rgba(16,185,129,0.2);">
                      <span style="width: 6px; height: 6px; background: var(--status-green); border-radius: 50%;"></span> OPTIMAL
                    </span>
                  </td>
                  <td style="padding: 16px 12px; border-bottom: 1px solid rgba(0,0,0,0.02); text-align: right; color: var(--text-bright);">12h 43m</td>
                </tr>
                <tr>
                  <td style="padding: 16px 12px; color: var(--text-bright);">sys-node-gamma</td>
                  <td style="padding: 16px 12px; color: var(--text-muted);">FastSpeech Synthesis</td>
                  <td style="padding: 16px 12px;">
                    <span style="display: inline-flex; align-items: center; gap: 6px; background: rgba(6,182,212,0.1); color: var(--neon-cyan); padding: 4px 8px; border-radius: 4px; border: 1px solid rgba(6,182,212,0.2);">
                      <span style="width: 6px; height: 6px; background: var(--neon-cyan); border-radius: 50%;"></span> STANDBY
                    </span>
                  </td>
                  <td style="padding: 16px 12px; text-align: right; color: var(--text-bright);">12h 43m</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>

    <!-- Right Panel (Logs) -->
    <aside class="app-right-panel">
      <div class="glass-panel" style="height: 100%; display: flex; flex-direction: column;">
        <div class="widget-title" style="margin-bottom: 16px;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="var(--text-bright)" style="margin-right: 8px;"><path d="M20 19V7H4v12h16m0-16c1.1 0 2 .9 2 2v14c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V5c0-1.1.9-2 2-2h16m-7 14v-2h5v2h-5m-3.42-4L5.57 9H8.4l3.3 3.3c.39.39.39 1.03 0 1.42L8.42 17H5.59l4.01-4z"/></svg>
          SYSTEM TERMINAL
        </div>
        <div class="stream-log" id="stream-log" style="flex: 1; border-top: 1px solid rgba(0,0,0,0.02); padding-top: 16px;">
          <div class="log-entry">
            <div class="log-time">00:00:00:00</div>
            <div class="log-text">SwarAI Core V2.5 initialized. Awaiting secure stream injection.</div>
          </div>
        </div>
      </div>
    </aside>

  </div>

  <script type="module" src="/js/main.js"></script>
</body>
</html>

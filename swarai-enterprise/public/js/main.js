console.log('SwarAI Enterprise Pro Interface Initialized');

document.addEventListener('DOMContentLoaded', () => {
  // Sidebar Interaction & View Switching
  const navItems = document.querySelectorAll('.nav-item');
  const viewSections = document.querySelectorAll('.view-section');

  navItems.forEach(item => {
    item.addEventListener('click', () => {
      // Update nav styling
      navItems.forEach(n => n.classList.remove('active'));
      item.classList.add('active');

      // Switch views
      const targetId = item.getAttribute('data-target');
      if (targetId) {
        viewSections.forEach(section => {
          section.classList.remove('active');
        });
        const targetSection = document.getElementById(targetId);
        if (targetSection) {
          targetSection.classList.add('active');
        }
      }
    });
  });

  // Start Stream Toggle
  const btnProcess = document.getElementById('btn-process');
  let isStreaming = false;
  let visualizerInterval = null;
  let mediaRecorder = null;
  let audioChunks = [];

  btnProcess.addEventListener('click', async () => {
    isStreaming = !isStreaming;
    
    if (isStreaming) {
      try {
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        mediaRecorder = new MediaRecorder(stream);
        audioChunks = [];
        
        mediaRecorder.ondataavailable = (event) => {
          if (event.data.size > 0) {
            audioChunks.push(event.data);
          }
        };

        mediaRecorder.onstop = async () => {
          const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
          addLogEntry('Processing captured audio via Gemini...');
          const reader = new FileReader();
          reader.readAsDataURL(audioBlob);
          reader.onloadend = async () => {
            const base64data = reader.result.split(',')[1];
            
            try {
              const response = await fetch(`${window.GO_ENGINE_URL}/api/process`, {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json'
                },
                body: JSON.stringify({ audio_data: base64data })
              });
              
              const result = await response.json();
              if (result.success) {
                addLogEntry('Translation Success: ' + result.translated_text);
              } else {
                addLogEntry('Error processing audio: ' + result.message);
              }
            } catch (err) {
              addLogEntry('Failed to connect to backend engine.');
              console.error(err);
            }
          };
        };

        mediaRecorder.start();
        btnProcess.textContent = 'TERMINATE INGESTION';
        btnProcess.classList.remove('btn-primary');
        btnProcess.classList.add('btn-outline');
        btnProcess.style.color = 'var(--alert-red)';
        btnProcess.style.borderColor = 'var(--alert-red)';
        
        startVisualizer();
        addLogEntry('Audio stream connected. Recording started...');
      } catch (err) {
        addLogEntry('Microphone access denied or error occurred.');
        console.error(err);
        isStreaming = false;
      }
    } else {
      if (mediaRecorder && mediaRecorder.state !== 'inactive') {
        mediaRecorder.stop();
        mediaRecorder.stream.getTracks().forEach(track => track.stop());
      }
      
      btnProcess.textContent = 'INITIATE INGESTION';
      btnProcess.classList.remove('btn-outline');
      btnProcess.classList.add('btn-primary');
      btnProcess.style.color = '#fff';
      btnProcess.style.borderColor = 'var(--neon-cyan)';
      
      stopVisualizer();
      addLogEntry('Stream disconnected. Analyzing payload...');
    }
  });

  // Audio Visualizer Simulation
  const visualizerContainer = document.getElementById('audio-visualizer');
  const numBars = 64;
  
  if (visualizerContainer) {
    // Create bars
    for (let i = 0; i < numBars; i++) {
      const bar = document.createElement('div');
      bar.className = 'visualizer-bar';
      visualizerContainer.appendChild(bar);
    }
  }

  const bars = document.querySelectorAll('.visualizer-bar');

  function startVisualizer() {
    visualizerInterval = setInterval(() => {
      bars.forEach((bar, index) => {
        // Create a wave-like random height
        const wave = Math.sin((index + Date.now() / 100) * 0.2) * 20;
        const randomHeight = Math.floor(Math.random() * 60) + 20;
        const height = Math.min(100, Math.max(10, randomHeight + wave));
        bar.style.height = `${height}%`;
      });
    }, 70);
  }

  function stopVisualizer() {
    clearInterval(visualizerInterval);
    bars.forEach(bar => {
      bar.style.height = '4px';
    });
  }

  // Stream Log Simulation
  const streamLog = document.getElementById('stream-log');
  
  function addLogEntry(text) {
    if (!streamLog) return;
    const entry = document.createElement('div');
    entry.className = 'log-entry';
    
    const time = document.createElement('div');
    time.className = 'log-time';
    
    // Generate a pseudo-timecode
    const now = new Date();
    const tc = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}:${String(now.getSeconds()).padStart(2, '0')}:${String(Math.floor(Math.random()*99)).padStart(2, '0')}`;
    time.textContent = tc;
    
    const content = document.createElement('div');
    content.className = 'log-text';
    content.textContent = text;
    
    entry.appendChild(time);
    entry.appendChild(content);
    
    streamLog.appendChild(entry);
    streamLog.scrollTop = streamLog.scrollHeight;
  }
});

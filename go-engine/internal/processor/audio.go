package processor

import (
	"context"
	"encoding/base64"
	"encoding/json"
	"fmt"
	"log"
	"net/http"
	"strings"

	"google.golang.org/genai"
)

type ProcessRequest struct {
	AudioData string `json:"audio_data"`
}

type ProcessResponse struct {
	Success        bool   `json:"success"`
	TranslatedText string `json:"translated_text"`
	AudioURL       string `json:"audio_url,omitempty"`
	Message        string `json:"message,omitempty"`
}

func HandleAudioProcess(w http.ResponseWriter, r *http.Request) {
	if r.Method != http.MethodPost {
		http.Error(w, "Method not allowed", http.StatusMethodNotAllowed)
		return
	}

	log.Println("Received request to process audio stream")

	var req ProcessRequest
	if err := json.NewDecoder(r.Body).Decode(&req); err != nil {
		http.Error(w, "Invalid request body", http.StatusBadRequest)
		return
	}

	// 1. Decode base64 audio data
	audioBytes, err := base64.StdEncoding.DecodeString(req.AudioData)
	if err != nil {
		log.Printf("Error decoding base64 audio: %v", err)
		http.Error(w, "Invalid audio data", http.StatusBadRequest)
		return
	}

	// 2. Initialize Gemini Client
	ctx := context.Background()
	client, err := genai.NewClient(ctx, nil)
	if err != nil {
		log.Printf("Error initializing Gemini client: %v", err)
		http.Error(w, "Internal Server Error", http.StatusInternalServerError)
		return
	}

	// 3. Process with Gemini
	prompt := "You are a specialized audio translator. Listen to the provided audio (which contains English speech) and translate it to Hindi. Respond ONLY with the translated Hindi text, nothing else."
	
	parts := []*genai.Part{
		{Text: prompt},
		{InlineData: &genai.Blob{Data: audioBytes, MIMEType: "audio/webm"}},
	}
	contents := []*genai.Content{{Parts: parts}}
	
	result, err := client.Models.GenerateContent(ctx, "gemini-3.7-flash", contents, nil)
	if err != nil {
		log.Printf("Error generating content from Gemini: %v", err)
		
		// Fallback for demo if Gemini fails (e.g. no API key or quota)
		fallbackResponse := ProcessResponse{
			Success:        false,
			Message:        fmt.Sprintf("API Error: %v", err),
		}
		w.Header().Set("Content-Type", "application/json")
		json.NewEncoder(w).Encode(fallbackResponse)
		return
	}

	// Extract the text
	var translatedText string
	if result != nil && len(result.Candidates) > 0 && result.Candidates[0].Content != nil {
		for _, part := range result.Candidates[0].Content.Parts {
			if part.Text != "" {
				translatedText += part.Text
			}
		}
	}
	
	translatedText = strings.TrimSpace(translatedText)
	if translatedText == "" {
		translatedText = "Could not extract text from audio."
	}

	response := ProcessResponse{
		Success:        true,
		TranslatedText: translatedText,
	}

	w.Header().Set("Content-Type", "application/json")
	if err := json.NewEncoder(w).Encode(response); err != nil {
		log.Printf("Error encoding response: %v", err)
		http.Error(w, "Internal Server Error", http.StatusInternalServerError)
	}
}

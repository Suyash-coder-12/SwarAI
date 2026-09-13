package main

import (
	"log"
	"net/http"

	"github.com/beatsvibe/go-engine/internal/processor"
)

func corsMiddleware(next http.Handler) http.Handler {
	return http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
		w.Header().Set("Access-Control-Allow-Origin", "*")
		w.Header().Set("Access-Control-Allow-Methods", "POST, GET, OPTIONS, PUT, DELETE")
		w.Header().Set("Access-Control-Allow-Headers", "Accept, Content-Type, Content-Length, Accept-Encoding, Authorization")

		if r.Method == "OPTIONS" {
			w.WriteHeader(http.StatusOK)
			return
		}

		next.ServeHTTP(w, r)
	})
}

func main() {
	mux := http.NewServeMux()

	// Health check endpoint
	mux.HandleFunc("/health", func(w http.ResponseWriter, r *http.Request) {
		w.WriteHeader(http.StatusOK)
		w.Write([]byte("Go Engine is healthy"))
	})

	// Process Audio API
	mux.HandleFunc("/api/process", processor.HandleAudioProcess)

	log.Println("SwarAI Go Engine listening on :9000")
	if err := http.ListenAndServe(":9000", corsMiddleware(mux)); err != nil {
		log.Fatalf("Server failed to start: %v", err)
	}
}

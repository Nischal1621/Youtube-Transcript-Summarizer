from flask import Flask, request, jsonify
from youtube_transcript_api import YouTubeTranscriptApi
from transformers import pipeline

app = Flask(__name__)

@app.route('/')
def index():
    return app.send_static_file('index.html')

@app.route('/summary', methods=['POST'])
def summarizeTranscript():
    data = request.json
    video_url = data.get('video_url')

    transcript = get_transcript(video_url)
    summary = get_summary(transcript)

    return jsonify({'summary': summary})

def get_transcript(video_url):
    video_id = video_url.split('=')[-1]  # Extract video ID from URL
    transcript_list = YouTubeTranscriptApi.get_transcript(video_id)
    transcript = ' '.join([d['text'] for d in transcript_list])
    return transcript

def get_summary(transcript):
    summarizer = pipeline('summarization')
    summary = summarizer(transcript, max_length=200, min_length=50, do_sample=False)[0]['summary_text']
    return summary

if __name__ == '__main__':
    app.run()
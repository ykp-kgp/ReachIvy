import requests

def get_video_url(api_key, video_id):
    api_url = f"https://api.vdocipher.com/v2/videos/{video_id}"
    headers = {
        'Authorization': f'Bearer {api_key}'
    }
    response = requests.get(api_url, headers=headers)
    response.raise_for_status()
    return response.json().get('videoUrl')

def download_video(video_url, output_path):
    response = requests.get(video_url, stream=True)
    response.raise_for_status()
    
    with open(output_path, 'wb') as file:
        for chunk in response.iter_content(chunk_size=8192):
            file.write(chunk)
    
    print(f"Download completed: {output_path}")

if __name__ == "__main__":
    # Hypothetical API key and video ID
    api_key = 'your_api_key'
    video_id = 'your_video_id'
    
    video_url = get_video_url(api_key, video_id)
    output_path = 'downloaded_video.mp4'
    download_video(video_url, output_path)

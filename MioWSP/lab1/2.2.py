import yt_dlp


def download_playlist_as_mp3(playlist_url,ffmpeg_path='C:/ffmpeg/bin'):
    ydl_opts = {
        'format': 'bestaudio/best',
        'extractaudio': True,
        'audioformat': 'mp3',
        'outtmpl': f'{.}/%(title)s.%(ext)s',
        'noplaylist': False,
        'postprocessors': [{
            'key': 'FFmpegExtractAudio',
            'preferredcodec': 'mp3',
            'preferredquality': '0',
        }],
        'ffmpeg_location': ffmpeg_path
    }

    with yt_dlp.YoutubeDL(ydl_opts) as ydl:
        ydl.download([playlist_url])


if __name__ == "__main__":
    playlist_url = "linkdoplalisty"
    download_playlist_as_mp3(playlist_url)
    print("Pobieranie zakończone!")

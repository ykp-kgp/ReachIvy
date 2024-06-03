import os
from PIL import Image

def compress_and_convert_image(input_folder, output_folder, target_reduction=0.3, initial_quality=85, step=5):
    # Create output folder if it doesn't exist
    if not os.path.exists(output_folder):
        os.makedirs(output_folder)
    
    # Loop through each file in the input folder
    for filename in os.listdir(input_folder):
        if filename.lower().endswith(('.png', '.jpeg', '.jpg')):
            file_path = os.path.join(input_folder, filename)
            
            # Get original file size
            original_size = os.path.getsize(file_path)
            target_size = original_size * (1 - target_reduction)
            
            # Open an image file
            with Image.open(file_path) as img:
                webp_filename = os.path.splitext(filename)[0] + '.webp'
                webp_filepath = os.path.join(output_folder, webp_filename)
                
                # Start with the initial quality and reduce it until the file size is close to the target size
                quality = initial_quality
                while quality > 0:
                    img.save(webp_filepath, 'webp', quality=quality)
                    new_size = os.path.getsize(webp_filepath)
                    if new_size <= target_size:
                        break
                    quality -= step
                
                print(f'Converted {filename} to {webp_filename} with final quality {quality}')

input_folder = 'Old Image'
output_folder = 'New Image'
compress_and_convert_image(input_folder, output_folder)


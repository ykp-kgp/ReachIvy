# import requests
# from bs4 import BeautifulSoup

# # Function to scrape essays from a website
# def scrape_essays(url):
#     # Set the user-agent header
#     headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3'}
    
#     # Send a GET request to the URL with headers
#     response = requests.get(url, headers=headers)
    
#     # Check if the request was successful
#     if response.status_code == 200:
#         # Parse the HTML content of the page
#         soup = BeautifulSoup(response.content, 'html.parser')
        
#         # Find all the links to the essays
#         essay_links = soup.find_all('h4', class_='hi-article-card-title')
        
#         if not essay_links:
#             print("No essay links found.")
#             print("Response content:")
#             print(response.content)
#             return
        
#         # Extract the URLs of the essays
#         for link in essay_links:
#             essay_url = link.a['href']
#             # You can then visit each essay URL to scrape its content
#     else:
#         print("Failed to retrieve page:", response.status_code)

#     return essay_links


# def scrape_essay_content(essay_url):
#     # Set the user-agent header
#     headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3'}
    
#     # Send a GET request to the essay URL with headers
#     response = requests.get(essay_url, headers=headers)
    
#     # Check if the request was successful
#     if response.status_code == 200:
#         # Parse the HTML content of the essay page
#         soup = BeautifulSoup(response.content, 'html.parser')
        
#         # Find the essay content between <h2> and <h4> tags
#         essay_content = soup.find('h2', class_='wp-block-heading').find_next_siblings('p')
        
#         if essay_content:
#             # Concatenate the text of all <p> tags
#             essay_text = ' '.join([p.get_text(strip=True) for p in essay_content])
#             print("Essay Content:", essay_text)
#         else:
#             print("Essay content not found.")
            
#     else:
#         print("Failed to retrieve essay page:", response.status_code)

# # Example usage

# list_essay = scrape_essays('https://apply.jhu.edu/hopkins-insider/application-section/essays-that-worked/')

# for essay in list_essay:
#     essay_url = essay.a['href']
#     scrape_essay_content(essay_url)



import requests
from bs4 import BeautifulSoup
import pandas as pd

# # Function to scrape essays from a website
# def scrape_essays(url):
#     # Set the user-agent header
#     headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3'}
    
#     # Send a GET request to the URL with headers
#     response = requests.get(url, headers=headers)
    
#     # Check if the request was successful
#     if response.status_code == 200:
#         # Parse the HTML content of the page
#         soup = BeautifulSoup(response.content, 'html.parser')
        
#         # Find all the links to the essays
#         essay_links = soup.find_all('h4', class_='hi-article-card-title')
        
#         if not essay_links:
#             print("No essay links found.")
#             print("Response content:")
#             print(response.content)
#             return
        
#         # Extract the URLs of the essays
#         essays_data = []
#         for link in essay_links:
#             essay_url = link.a['href']
#             essay_content = scrape_essay_content(essay_url)
#             essays_data.append((essay_url, essay_content))
        
#         return essays_data
#     else:
#         print("Failed to retrieve page:", response.status_code)
#         return None

# def scrape_essay_content(essay_url):
#     # Set the user-agent header
#     headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3'}
    
#     # Send a GET request to the essay URL with headers
#     response = requests.get(essay_url, headers=headers)
    
#     # Check if the request was successful
#     if response.status_code == 200:
#         # Parse the HTML content of the essay page
#         soup = BeautifulSoup(response.content, 'html.parser')
        
#         # Find the essay content between <h2> and <h4> tags
#         essay_content = soup.find('div', class_='callout mt-3')
        
#         if essay_content:
#             # Extract the text of all <p> tags
#             paragraphs = essay_content.find_all('p')
#             essay_text = ' '.join([p.get_text(strip=True) for p in paragraphs])
#             return essay_text
#         else:
#             print("Essay content not found.")
#             return None
#     else:
#         print("Failed to retrieve essay page:", response.status_code)
#         return None

# # Example usage
# essays_data = scrape_essays('https://apply.jhu.edu/hopkins-insider/application-section/essays-that-worked/page/6/')

# if essays_data:
#     # Create a DataFrame from the scraped data
#     df = pd.DataFrame(essays_data, columns=['Essay URL', 'Essay Content'])
    
#     # Save the DataFrame to an Excel file
#     df.to_excel('essays6.xlsx', index=False)
#     print("Essays saved to essays.xlsx")


import requests
from bs4 import BeautifulSoup

# Function to scrape prompt and essay content from a website
def scrape_prompt_and_essay(url):
    # Set the user-agent header
    headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3'}
    
    # Send a GET request to the URL with headers
    response = requests.get(url, headers=headers)
    
    # Check if the request was successful
    if response.status_code == 200:
        # Parse the HTML content of the page
        soup = BeautifulSoup(response.content, 'html.parser')
        
        # Find the prompt element
        prompt_element = soup.find('div', class_='callout mt-3').find('p').get_text()
        
        # Find the essay content element
        essay_content_element = soup.find('div', class_='callout').find_all('p')[1].get_text()
        
        return prompt_element, essay_content_element
    else:
        print("Failed to retrieve page:", response.status_code)
        return None, None

# Example usage
prompt, essay_content = scrape_prompt_and_essay('https://blog.collegevine.com/university-of-washington-essay-examples/#punks')

if prompt and essay_content:
    print("Prompt:")
    print(prompt)
    print("\nEssay Content:")
    print(essay_content)

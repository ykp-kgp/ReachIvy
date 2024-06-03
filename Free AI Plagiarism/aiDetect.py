# import requests

# url = "https://zerogpt.p.rapidapi.com/api/v1/detectText"

# payload = { "input_text": "C++ is a high-level, general-purpose programming language that was developed by Bjarne Stroustrup in 1983 as an extension of the C programming language. It is an object-oriented language that allows developers to write efficient and portable code that can run on a wide range of platforms, from embedded systems to supercomputers. In this article, we will discuss some of the key features and benefits of C++, as well as its various applications and use cases." }
# headers = {
# 	"content-type": "application/json",
# 	"ATBBSsz93c01QNyT9YmehEvtR0QNcTeB2D42E40": "ATBBSsz93c01QNyT9YmehEvtR0QNcTeB2D42E40",
# 	"X-RapidAPI-Key": "b383ffd0f9mshc5ee5a3a361c4b4p1f7f12jsn1237ad69a5ce",
# 	"X-RapidAPI-Host": "zerogpt.p.rapidapi.com"
# }

# response = requests.post(url, json=payload, headers=headers)

# print(response.json())


import requests

url = "https://grammar-corrector-api.p.rapidapi.com/spelling/check"

payload = {
	"text": "is my text is korect ?",
	"key2": "English"
}
headers = {
	"content-type": "application/json",
	"X-RapidAPI-Key": "b383ffd0f9mshc5ee5a3a361c4b4p1f7f12jsn1237ad69a5ce",
	"X-RapidAPI-Host": "grammar-corrector-api.p.rapidapi.com"
}

response = requests.post(url, json=payload, headers=headers)

print(response.json())


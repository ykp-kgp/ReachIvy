#!/usr/bin/python
import sys
import os
import json

import codecs
import re

import collections
# used this file to support python 2.6 as python 2.6 does not have collecions.Counter()
from counter import *
# import docx
# import docx2txt
d={}
s={}
lines, blanklines, sentences, words = 0, 0, 0, 0
numbers = ['0','1','2','3','4','5','6','7','8','9']
punctuation = [',','.',';',':']
punctuation2 = [',','!','.','',';','-','s',':','I','The','would','I','want','In']

stop_words=[]
f=open('english', 'r')
for line in f:
  stop_words.append(line)
stop_words = [word.strip() for word in stop_words]
# stop_words = stopwords.words('english')
stop_words.extend(numbers)
stop_words.extend(punctuation2)
stop_words = set(stop_words)
filtered_sentence=[]
commaSentences=[]
commaError=[]
synSentence=[]
longPara=[]
# print '-' * 50
try:
  # use a text file you have, or google for this one ...
  filename = sys.argv[1]
  # filename = 'words.txt'
  textf = codecs.open(filename, 'r', encoding="utf-8")
except IOError:
  print(f'Cannot open file {filename} for reading')
  sys.exit(0)
# textf = docx2txt.process("words.docx")
exclSent=[]
longSentence=[]
# reads one line at a time
for line in textf:
  # print line,   # test
  lines += 1

  if line.split() == []:
  # if line.startswith('\n'):
    blanklines += 1 
  else:
    # assume that each sentence ends with . or ! or ?
    # so simply count these characters
    sentences += line.count('.') + line.count('!') + line.count('?')
    falseSentences = len(re.findall(r'\d\.\d+', line))
    sentences = sentences - falseSentences
    # for finding numbers
    allSentences = line.split('.')
    # print 'allsent1:'+str(len(allSentences))
    for sent in allSentences:
      sentArr = sent.split()
      excl=0
      for sentArrElement in sentArr:
        for sentArrElementElement in sentArrElement:
          if sentArrElementElement=='!':
              excl=1
      if excl==1:
        temp=sent.split('!')
        sent2 = temp[0]+'!'
        exclSent.append(sent2)

    for n, i in enumerate(allSentences):
      e = i.split('!')
      f = i.split('?')
      if len(e)>1:
        allSentences[n:n+len(e)-1] = e

      if len(f)>1:
        allSentences[n:n+len(f)-1] = f
      # print allSentences
    # print 'allsent2:'+str(len(allSentences))
    sentNumber=0
    done=0
    for sent in allSentences:
      sentNumber+=1
      sentArr = sent.split()
      numeric=[]
      wordCount=0
      commas=0
      commae=0
      syn=0
      if sentNumber>8 and done==0:
        longPara.append(allSentences[0])
        done=1
      for sentArrElement in sentArr:
        wordCount+=1
        if sentArrElement in numbers:
          numeric.append(sentArrElement)
        for n, sentArrElementElement in enumerate(sentArrElement):
          if sentArrElementElement==',':
            commas+=1
        if sentArrElement==',':
          commae=1
        if sentArrElement=='nice' or sentArrElement=='interesting' or sentArrElement=='unique' or sentArrElement=='bad' or sentArrElement=='important':
          syn=1
        if sentArrElement not in stop_words:
          filtered_sentence.append(sentArrElement)

      if numeric!=[]:
        s[sent]=numeric
      # yoyooyyo
      if  wordCount>18:
        longSentence.append(sent)
      if commas>=4:
        commaSentences.append(sent)
      if commae==1:
        commaError.append(sent)
      if syn==1:
        synSentence.append(sent)
      # t = re.findall(r'\s+\d\s+', sent)
      # if t!=[]:
      #   numeric=[]
      #   numeric.append(t)
      #   s[sent]=numeric
    d['sentences2']=s
    d['exclamation']=exclSent
    d['longSentence']=longSentence
    d['commaSentences']=commaSentences
    d['commaError']=commaError
    d['synSentence']=synSentence
    d['longPara']=longPara
    # create a list of words
    # use None to split at any whitespace regardless of length
    # so for instance double space counts as one space
    # word_tokens = word_tokenize(line)
    # for w in word_tokens:
    #   if w not in stop_words:
    #     filtered_sentence.append(w)
    line = re.sub(r'[^\w\s]',' ',line)

    tempwords = line.split(None)
    # print tempwords  # test
    # word total count
    words += len(tempwords)
    
repeatWord={}
word_counts = Counter(filtered_sentence)
for word, count in sorted(word_counts.items()):
  if count>2:
    # repeatWord[word]=count
    repeatWord[word]='"%s" is repeated %d time%s.' % (word, count, "s" if count > 1 else "")
    # print '"%s" is repeated %d time%s.' % (word, count, "s" if count > 1 else "")
d['repeatWords']=repeatWord

textf.close()

# print numeric
# print '-' * 50
# print "Lines      : ", lines
# print "Blank lines: ", blanklines
# print "Sentences  : ", sentences
# print "Words      : ", words


d['lines'] = lines
d['blanklines'] = blanklines
d['paragraphs'] = lines-blanklines
d['sentences'] = sentences
d['words'] = words

data = json.dumps(d)
print(data)

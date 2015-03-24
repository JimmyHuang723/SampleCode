from django.shortcuts import render

# Create your views here

from django.http import HttpResponse
from django.views.decorators.csrf import csrf_exempt
from rest_framework.renderers import JSONRenderer
from rest_framework.parsers import JSONParser
from snippets.models import Snippet
from snippets.serializers import SnippetSerializer

from rest_framework import status
from rest_framework.decorators import api_view
from rest_framework.response import Response

from django.http import Http404
from rest_framework.views import APIView

import threading


import sys
import os
import re
#from smtplib import SMTP_SSL as SMTP       # this invokes the secure SMTP protocol (port 465, uses SSL)
from smtplib import SMTP                  # use this for standard SMTP protocol   (port 25, no encryption)
from email.MIMEText import MIMEText

import pymongo

from time import gmtime, strftime

import ldap

from django.contrib.auth.models import User
from snippets.serializers import UserSerializer
from rest_framework import permissions
from rest_framework import generics
from snippets.permissions import IsOwnerOrReadOnly

from rest_framework.decorators import api_view
from rest_framework.response import Response
from rest_framework.reverse import reverse
from rest_framework import renderers

myTimer = None
timerRunning = False




class UserList(generics.ListAPIView):
    queryset = User.objects.all()
    serializer_class = UserSerializer


class UserDetail(generics.RetrieveAPIView):
    queryset = User.objects.all()
    serializer_class = UserSerializer

class SnippetList(APIView):
    """
    List all snippets, or create a new snippet.
    """
    permission_classes = (permissions.IsAuthenticatedOrReadOnly,)

    def printit(self):
        print strftime("%Y-%m-%d %H:%M:%S", gmtime())
        print "Timer running..."    

        global myTimer        
        global timerRunning
        myTimer = threading.Timer(3.0, self.printit)
        myTimer.start()
        timerRunning = True

    def startTimer(self):
        global myTimer
        global timerRunning
        if myTimer == None:
            myTimer = threading.Timer(3.0, self.printit)
            myTimer.start()
            timerRunning = True
            print "Timer started!"
        else:
            print "Timer not started, because it already exists!"

    def sendMail(self):
	SMTPserver = '123.1.11.22'
	sender =     'xxx@gmail.com'
	
        destination = ['yyy@gmail.com']	

	USERNAME = "nnnn"
	PASSWORD = "xxxx"
	
	# typical values for text_subtype are plain, html, xml
	text_subtype = 'plain'
	
	content="""\
	Test message
	"""
	subject="Sent from Python"
	try:
            print "start sending mail"
	    msg = MIMEText(content, text_subtype)
	    msg['Subject'] = subject
	    msg['From'] = sender # some SMTP servers will do this automatically, not all
	
	    conn = SMTP()
            print "init SMTP done"
	    #conn.set_debuglevel(False)
            conn.connect(SMTPserver, 25)
            print "connected"
	    #conn.login(USERNAME, PASSWORD)
            #print "login done"
	    try:
	        conn.sendmail(sender, destination, msg.as_string())
                print "email sent"
	    finally:
	        conn.close()
	
	except Exception, exc:
	    sys.exit( "mail failed; %s" % str(exc) ) # give a error message

    def mongo(self):
        client = pymongo.MongoClient("localhost", 27017)
        db = client.local
        my_collection = db.my_collection
        my_collection.save({"x": 8})
        my_collection.save({"x": 11})
        
        for item in my_collection.find():
            print item

    def ldap(self):
        con = ldap.initialize('ldap://77.2.22.5')
        con.simple_bind_s( 'CN=Service Account,OU=ServiceAccounts,OU=XXXX,OU=Special,DC=XXX,DC=corp', 'password')
        
        base_dn = "OU=TW,DC=XXX,DC=corp"
        attrs = ['sn']
        my_filter = 'cn=Jimmy*'
        SearchResult = con.search_s( base_dn, ldap.SCOPE_SUBTREE, my_filter, attrs)
        print "ldap search done"
        print SearchResult

    def ldap2(self):
        try:
            con = ldap.initialize('ldap://77.2.22.5')
            result = con.simple_bind_s( 'CN=Jimmy.Cheng,OU=XXXX,OU=YYYY,OU=TTT,OU=Taiwan,OU=TW,DC=XXX,DC=corp', 'password')
            print result
            print "ldap bind done"
        except:
            print "Bind exception : check your password" 

    def get(self, request, format=None):
        #print "Test ldap..."
        #self.ldap2()
        #return Response("done")

        #print "Test Timer..."
        #print strftime("%Y-%m-%d %H:%M:%S", gmtime())
        #global timerRunning        
        #global myTimer
        #if timerRunning == True:
        #    myTimer.cancel()
        #    print "timer cancelled"
        #    timerRunning = False
        #    myTimer = None
        #    return Response("timer cancelled")
        #else:
        #    self.startTimer()
        #    return Response("timer started")


        #print "Test email sending..."
        #self.sendMail()
        #return Response("done")


        #print "Test mongo..."
        #self.mongo()
        #return Response("done")
        
        # if 'xxx' in request.query_params:
              pass
              

        snippets = Snippet.objects.all()
        serializer = SnippetSerializer(snippets, many=True)
        return Response(serializer.data)

    def post(self, request, format=None):
        print 'post!!!'
        serializer = SnippetSerializer(data=request.data)
        if serializer.is_valid():
            #serializer.save()
            serializer.save(owner=self.request.user)
            return Response(serializer.data, status=status.HTTP_201_CREATED)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

    '''
    def put(self, request, format=None):
        # !!! Be sure to set Content-Type as json for the put request!!
        print "All callable methods for request object: "
        print [method for method in dir(request) if callable(getattr(request, method))]
        
        print "All attributes for request object: "
        print dir(request)

        print  request.query_params

        print "request content type : " + request.content_type  

        print  "keys : "
        print  request.data.keys()

        print  "values : " 
        print request.data.values()

        for k in request.data.keys():
            print "print each value : "  + request.data[k]

        return Response(request.data)
    '''

    
    def perform_create(self, serializer):
        print 'perform_create!!!!!'
        serializer.save(owner=self.request.user)
    
    def pre_save(self, snip):
        print 'pre_save!!!!!' 
        snip.owner = self.request.user

class SnippetDetail(APIView):
    """
    Retrieve, update or delete a snippet instance.
    """
    permission_classes = (permissions.IsAuthenticatedOrReadOnly, IsOwnerOrReadOnly,)

    def get_object(self, pk):
        try:
            return Snippet.objects.get(pk=pk)
        except Snippet.DoesNotExist:
            raise Http404

    def get(self, request, pk, format=None):
        snippet = self.get_object(pk)
        serializer = SnippetSerializer(snippet)
        return Response(serializer.data)

    def put(self, request, pk, format=None):
        snippet = self.get_object(pk)
        serializer = SnippetSerializer(snippet, data=request.data)
        if serializer.is_valid():
            serializer.save()
            return Response(serializer.data)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

    def delete(self, request, pk, format=None):
        snippet = self.get_object(pk)
        snippet.delete()
        return Response(status=status.HTTP_204_NO_CONTENT)



if __name__ == '__main__':
    s = SnippetList()
    s.sendMail()


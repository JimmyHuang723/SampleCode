# -*- coding: utf-8 -*-
from __future__ import unicode_literals
from django.shortcuts import render
from django.http import HttpResponse, HttpResponseRedirect, JsonResponse
import json

from rest_framework import status
from rest_framework.decorators import api_view
from rest_framework.response import Response


@api_view(['GET', 'POST','PUT','DELETE'])
def test_api(request):
    if request.method == 'GET':
        return JsonResponse({'foo': 'bar'})
        #return HttpResponse("GET res")
    elif request.method == 'POST':
        return HttpResponse(request.body)
    elif request.method == 'PUT':
        data = json.loads(request.body)
        return JsonResponse(data)
    elif request.method == 'DELETE':
        return HttpResponse("DEL res")



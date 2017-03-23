#-*- coding: utf-8 -*-
from django.core.urlresolvers import reverse
from django.http import HttpResponseRedirect
from django.shortcuts import get_object_or_404, render
from django.views import generic

from .models import Bannervideo


class IndexView(generic.ListView):

    def get_queryset(self):
        return Bannervideo.objects.all()[:5]


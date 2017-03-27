#-*- coding: utf-8 -*-
from django.core.urlresolvers import reverse
from django.http import HttpResponseRedirect
from django.shortcuts import get_object_or_404, render
from django.views import generic
from .models import Aboutus


class IndexView(generic.ListView):

    def get_queryset(self):
        return Aboutus.objects.all()[:1]
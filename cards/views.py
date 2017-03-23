#-*- coding: utf-8 -*-
from django.core.urlresolvers import reverse
from django.http import HttpResponseRedirect
from django.shortcuts import get_object_or_404, render
from django.views import generic

from .models import Cards


class IndexView(generic.ListView):
    template_name = 'ecardcanvas/editor.html'

    def get_queryset(self):
        return Cards.objects.all()[:5]


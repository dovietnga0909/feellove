from django.http import HttpResponse
from django.shortcuts import render, get_object_or_404
 
from chat.models import Room , Message
 
def index(request):
  chat_rooms = Room.objects.order_by('name')[:5]
  context = {
    'chat_list': chat_rooms,
  }
  return render(request,'chat/index.html', context)
 
def chat_room(request, room_id):
  chat = get_object_or_404(Room, pk=room_id)
  return render(request, 'chat/chat_room.html', {'chat': chat})

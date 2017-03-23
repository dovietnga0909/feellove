# -*- coding: utf-8 -*-
from __future__ import unicode_literals

from django.db import migrations, models
from django.conf import settings


class Migration(migrations.Migration):

    dependencies = [
        ('cards', '0001_initial'),
    ]

    operations = [
        migrations.AlterField(
            model_name='cards',
            name='created_by',
            field=models.ForeignKey(blank=True, to=settings.AUTH_USER_MODEL, unique=True),
        ),
        migrations.AlterField(
            model_name='cards',
            name='images',
            field=models.TextField(blank=True),
        ),
        migrations.AlterField(
            model_name='cards',
            name='tags',
            field=models.TextField(blank=True),
        ),
    ]

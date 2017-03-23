# -*- coding: utf-8 -*-
from __future__ import unicode_literals

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
    ]

    operations = [
        migrations.CreateModel(
            name='Bannervideo',
            fields=[
                ('id', models.AutoField(verbose_name='ID', serialize=False, auto_created=True, primary_key=True)),
                ('name', models.CharField(max_length=255)),
                ('bannerurl', models.TextField()),
                ('is_active', models.BooleanField(default=False)),
                ('link_fb', models.TextField()),
                ('link_google', models.TextField()),
                ('link_twitter', models.TextField()),
            ],
        ),
    ]

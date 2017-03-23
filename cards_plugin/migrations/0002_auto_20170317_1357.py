# -*- coding: utf-8 -*-
from __future__ import unicode_literals

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('cards_plugin', '0001_initial'),
    ]

    operations = [
        migrations.RenameField(
            model_name='cardspluginmodel',
            old_name='card',
            new_name='cards',
        ),
    ]

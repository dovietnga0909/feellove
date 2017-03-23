# -*- coding: utf-8 -*-
from __future__ import unicode_literals

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('cms', '0016_auto_20160608_1535'),
        ('cards', '0001_initial'),
    ]

    operations = [
        migrations.CreateModel(
            name='CardsPluginModel',
            fields=[
                ('cmsplugin_ptr', models.OneToOneField(parent_link=True, related_name='cards_plugin_cardspluginmodel', auto_created=True, primary_key=True, serialize=False, to='cms.CMSPlugin')),
                ('card', models.ForeignKey(to='cards.Cards')),
            ],
            options={
                'abstract': False,
            },
            bases=('cms.cmsplugin',),
        ),
    ]

# -*- coding: utf-8 -*-
from __future__ import unicode_literals

from django.db import models, migrations


class Migration(migrations.Migration):

    dependencies = [
        ('contact', '0001_initial'),
        ('cms', '0027_auto_20170402_2123'),
        ('one_page_plugin', '0001_initial'),
    ]

    operations = [
        migrations.CreateModel(
            name='ContactPluginModel',
            fields=[
                ('cmsplugin_ptr', models.OneToOneField(parent_link=True, related_name='one_page_plugin_contactpluginmodel', auto_created=True, primary_key=True, serialize=False, to='cms.CMSPlugin')),
                ('contact', models.ForeignKey(to='contact.Contact')),
            ],
            options={
                'abstract': False,
            },
            bases=('cms.cmsplugin',),
        ),
    ]

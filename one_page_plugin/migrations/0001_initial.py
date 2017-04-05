# -*- coding: utf-8 -*-
from __future__ import unicode_literals

from django.db import models, migrations


class Migration(migrations.Migration):

    dependencies = [
        ('service', '0001_initial'),
        ('cms', '0030_auto_20170405_2109'),
        ('contact', '0002_contact_link_google_map'),
        ('partner', '0001_initial'),
        ('aboutus', '0003_counter'),
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
        migrations.CreateModel(
            name='OnePagePluginModel',
            fields=[
                ('cmsplugin_ptr', models.OneToOneField(parent_link=True, related_name='one_page_plugin_onepagepluginmodel', auto_created=True, primary_key=True, serialize=False, to='cms.CMSPlugin')),
                ('aboutus', models.ForeignKey(to='aboutus.Aboutus')),
            ],
            options={
                'abstract': False,
            },
            bases=('cms.cmsplugin',),
        ),
        migrations.CreateModel(
            name='PartnerPluginModel',
            fields=[
                ('cmsplugin_ptr', models.OneToOneField(parent_link=True, related_name='one_page_plugin_partnerpluginmodel', auto_created=True, primary_key=True, serialize=False, to='cms.CMSPlugin')),
                ('partner', models.ForeignKey(to='partner.Partner')),
            ],
            options={
                'abstract': False,
            },
            bases=('cms.cmsplugin',),
        ),
        migrations.CreateModel(
            name='ServicePluginModel',
            fields=[
                ('cmsplugin_ptr', models.OneToOneField(parent_link=True, related_name='one_page_plugin_servicepluginmodel', auto_created=True, primary_key=True, serialize=False, to='cms.CMSPlugin')),
                ('service', models.ForeignKey(to='service.Service')),
            ],
            options={
                'abstract': False,
            },
            bases=('cms.cmsplugin',),
        ),
    ]

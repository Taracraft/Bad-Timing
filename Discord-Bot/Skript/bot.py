# -*- coding:utf-8 -*-
# Google
# https://developers.google.com/sheets/api/quickstart/python
#
from __future__ import print_function

import json
import os.path

#import columns as columns
from discord.abc import Messageable
from googleapiclient.discovery import build
from google_auth_oauthlib.flow import InstalledAppFlow
from google.auth.transport.requests import Request
from google.oauth2.credentials import Credentials
# discord
import asyncio
import discord
import datetime
import logging
from discord import Member, channel, message
from discord.ext.commands import MissingPermissions, bot, has_permissions

intents = discord.Intents.all()
intents.members = True
intents.messages = True
intents.presences = True
client = discord.Client(intents=discord.Intents.all())
g = client.get_guild(759212028744695808)
logger = logging.getLogger('discord')
logger.setLevel(logging.INFO)
handler = logging.FileHandler(filename='discord.log', encoding='utf-8', mode='w')
handler.setFormatter(logging.Formatter('%(asctime)s:%(levelname)s:%(name)s: %(message)s'))
logger.addHandler(handler)


@client.event
async def status_task():
    while True:
        await client.change_presence(activity=discord.Game('https://www.bad-timing.eu'), status=discord.Status.online)
        await asyncio.sleep(10)
        await client.change_presence(activity=discord.Game('User & Google-Bot'), status=discord.Status.online)
        await asyncio.sleep(5)


@client.event
async def on_member_join(member: discord.Member):
    role = discord.utils.get(member.guild.roles, name="Spieler")
    channel = client.get_channel(812581364799897621)
    if member.guild.id == 759212028744695808:
        await member.add_roles(role)
        await channel.send(f'**Hey! {member.name}**\n Willkommen auf dem Discord Server von Bad-Timing! \n Viel Spaß!')


@client.event
async def on_message(message):
    global g




    if message.author.bot:
        return
    if message.content.lower() == "!help":
        await message.channel.send('**Hilfe zum Bad-Timing-Bot**\n\n'
                                   '$help zeigt diese Hilfe an.')

    if message.content.lower().startswith("!spieler"):
        args = message.content.split(' ')
        if len(args) == 2:
            member: Member = discord.utils.find(lambda m: args[1] in m.name,
                                                g.members)
            if member:
                role = discord.utils.get(g.roles, name="Spieler")
                await message.author.add_roles(role)
                await message.channel.send(f'{message.author} wurde zu {role} hinzugefügt')
        else:
            await message.channel.send('Benutzung: !spieler <NAME>')

    if message.content.lower().startswith("!supporter"):
        args = message.content.split(' ')
        if len(args) == 2:
            member: Member = discord.utils.find(lambda m: args[1] in m.name,
                                                g.members)
            if member:
                role = discord.utils.get(g.roles, name="Supporter")
                await message.author.add_roles(role)
                await message.channel.send(f'{member.name} wurde zu {role} hinzugefügt')
        else:
            await message.channel.send('Benutzung: !supporter <NAME>')

    if message.content.lower().startswith("!builder"):
        args = message.content.split(' ')
        if len(args) == 2:
            member: Member = discord.utils.find(lambda m: args[1] in m.name,
                                                g.members)
            if member:
                role = discord.utils.get(g.roles, name="Builder")
                await member.add_roles(role)
                await message.channel.send(f'{member.name} wurde zu {role} hinzugefügt')
        else:
            await message.channel.send('Benutzung !builder <NAME>')

    if message.content.lower().startswith("!developer"):
        args = message.content.split(' ')
        if len(args) == 2:
            member: Member = discord.utils.find(lambda m: args[1] in m.name,
                                                g.members)
            if member:
                role = discord.utils.get(g.roles, name="Developer")
                await message.author.add_roles(role)
                await message.channel.send(f'{message.author} wurde zu {role} hinzugefügt')
        else:
            await message.channel.send('Benutzung: !developer <NAME>')

    if message.content.lower().startswith("!admin"):
        args = message.content.split(' ')
        if len(args) == 2:
            member: Member = discord.utils.find(lambda m: args[1] in m.name,
                                                g.members)
            if member:
                role = discord.utils.get(g.roles, name="Admin")
                await message.author.add_roles(role)
                await message.channel.send(f'{message.author} wurde zu {role} hinzugefügt')
        else:
            await message.channel.send('Benutzung: !admin <NAME>')

    if message.content.lower().startswith("!head-builder"):
        args = message.content.split(' ')
        if len(args) == 2:
            member: Member = discord.utils.find(lambda m: args[1] in m.name,
                                                g.members)
            if member:
                role = discord.utils.get(g.roles, name="Head-Builder")
                await message.author.add_roles(role)
                await message.channel.send(f'{message.author} wurde zu {role} hinzugefügt')
        else:
            await message.channel.send('Benutzung: !head-builder <NAME>')

    if message.content.lower().startswith("!userinfo"):
        args = message.content.split(' ')
        if len(args) == 2:
            member: Member = discord.utils.find(lambda m: args[1] in m.name,
                                                g.members)
            if member:
                embed = discord.Embed(title='Benutzerinformationen für {}'.format(member.name),
                                      description='Information über deinen User {}'.format(member.mention),
                                      color=0x22a7f0)
                embed.add_field(name='Server beigetreten', value=member.joined_at.strftime('%d/%m/%Y, %H:%M:%S'),
                                inline=True)
                embed.add_field(name='Discord beigetreten', value=member.created_at.strftime('%d/%m/%Y, %H:%M:%S'),
                                inline=True)
                rollen = ''
                for role in member.roles:
                    if not role.is_default():
                        rollen += '{} \r\n'.format(role.mention)
                if rollen:
                    embed.add_field(name='Rollen', value=rollen, inline=True)
                embed.set_thumbnail(url=member.avatar_url)
                embed.set_footer(text='by https://www.Bad-Timing.eu')
                mess = await message.channel.send(embed=embed)
                await mess.add_reaction(':100:')
        else:
            await message.channel.send('Benutzung: !userinfo <NAME>')

    if message.content.lower().startswith("!kick"):
        @bot.command(name="!kick", pass_context=True)
        @has_permissions(manage_roles=True, ban_members=True)
        async def _kick(ctx, member: Member):
            await bot.kick(member)

        @_kick.error
        async def kick_error(error, ctx):
            if isinstance(error, MissingPermissions):
                text = "Sorry {}, you do not have permissions to do that!".format(ctx.message.author)
                await bot.send_message(ctx.message.channel, text)
    if message.content.lower().startswith("!del-log"):
        arr = [client.get_channel(901942693750509578)]
        for channel in arr:
            print('Clearing messages...')
            await channel.purge(limit=100000)
        else:
            print(channel, 'Keine Einträge in Admin-Log gefunden')
    if not message.content.startswith("!"):
        guild = message.guild
        log_channel = client.get_channel(901942693750509578)
        if log_channel is None:
            await client.process_commands(message)
            return
        if not message.author.bot:
            embed = discord.Embed(
                color=0xffd700,
                timestamp=datetime.datetime.utcnow(),
                description="in {}:\n{}".format(message.channel, message.content)
            )
            embed.set_author(name=message.author, icon_url=message.author.avatar_url)
            embed.set_footer(text=message.author.id)
            if len(message.attachments) > 0:
                embed.set_image(url=message.attachments[0].url)
            await log_channel.send(embed=embed)


def is_not_pinned(mess):
    return not mess.pinned

# google-sheet

# If modifying these scopes, delete the file token.json.
SCOPES = ['https://www.googleapis.com/auth/spreadsheets.readonly']

# The ID and range of a sample spreadsheet.
SAMPLE_SPREADSHEET_ID = '1dwI3YjMYSsyr3PbkYN5LXKP-cAkXpbLsSmpo776beRE'
Tabelle_1 = ("Formularantworten1!A1:C100")
Tabelle_2 = ("Titelblatt!A1:C100")
Tabelle_3 = ("To-Do(Allgemein)!A2:D100")
Tabelle_4 = ("To-Do(Bauen)!A1:C100")
Tabelle_5 = ("Info-Bauen!A1:B100")
Tabelle_6 = ("To-Do(Entwickeln)!A1:D100")



def main():
    """Shows basic usage of the Sheets API.
    Prints values from a sample spreadsheet.
    """
    @client.event
    async def on_ready():
        global g
        print("Bot is ready!")
        print("Logged in as: " + client.user.name)
        print("Bot ID: " + str(client.user.id))
        for guild in client.guilds:
            print("Connected to server: {}".format(guild))
        print("------")
        client.loop.create_task(status_task())

        # delete
        arr = [client.get_channel(906898916639920148),
               client.get_channel(906899134689214484),
               client.get_channel(906898823044014130),
               client.get_channel(906899000047861790),
               client.get_channel(906899062006116372),
               client.get_channel(906899213588254761)]


        for channel in arr:
            print('Clearing messages...')
            await channel.purge(limit=1000)
        else:
            print(channel, 'Keine Einträge gefunden')
        creds = None
        # The file token.json stores the user's access and refresh tokens, and is
        # created automatically when the authorization flow completes for the first
        # time.
        if os.path.exists('token.json'):
            creds = Credentials.from_authorized_user_file('token.json', SCOPES)
        # If there are no (valid) credentials available, let the user log in.
        if not creds or not creds.valid:
            if creds and creds.expired and creds.refresh_token:
                creds.refresh(Request())
            else:
                flow = InstalledAppFlow.from_client_secrets_file(
                    'credentials.json', SCOPES)
                creds = flow.run_local_server(port=0)
            # Save the credentials for the next run
            with open('token.json', 'w') as token:
                token.write(creds.to_json())
        
        service = build('sheets', 'v4', credentials=creds)
        
        # Call the Sheets API           Tabelle=Formularantworten1




#
        sheet = service.spreadsheets()
        result = sheet.values().get(spreadsheetId=SAMPLE_SPREADSHEET_ID,
                                    range=Tabelle_1).execute()
        values = result.get('values', [])

        if not values:
            print('No data found.')
        else:
            for row in values:
                if not row or row[0] != 'FALSE' or len(row[1]) == 0:
                    continue
                embed = discord.Embed(title='Folgende Daten gefunden: ',
                                      description='{}: {}'.format(row[1], row[2]),
                                      color=0x22a7f0)
                embed.set_thumbnail(url="https://cdn.discordapp.com/embed/avatars/0.png")
                embed.set_footer(text="by https://www.Bad-Timing.eu")
                channel = client.get_channel(906899213588254761)
                await channel.send(embed=embed)
        
        sheet = service.spreadsheets()          #Tabelle=Titelblatt
        result = sheet.values().get(spreadsheetId=SAMPLE_SPREADSHEET_ID,
                                    range=Tabelle_2).execute()
        values = result.get('values', [])

        if not values:
            print('No data found.')
        else:
            for row in values:
                if not row or row[0] != 'FALSE' or len(row[1]) == 0:
                    continue
                embed = discord.Embed(title='Folgende Daten gefunden: ',
                                      description='{}: {}'.format(row[1], row[2]),
                                      color=0x22a7f0)

                embed.set_thumbnail(url="https://cdn.discordapp.com/embed/avatars/0.png")
                embed.set_footer(text="by https://www.Bad-Timing.eu")
                channel = client.get_channel(906899134689214484)
                await channel.send(embed=embed)
        
        sheet = service.spreadsheets()              #Tabelle=Todo(Allgemein)
        result = sheet.values().get(spreadsheetId=SAMPLE_SPREADSHEET_ID,
                                    range=Tabelle_3).execute()
        values = result.get('values', [])
        if not values:
            print('No data found.')
        else:
            for row in values:
                if not row or row[0] != 'FALSE' or len(row[1]) == 0:
                    continue
                embed = discord.Embed(title='Folgende Daten gefunden: ',
                        description='{}: {}'.format(row[1],row[2]),
                                      color=0x22a7f0)

                embed.set_thumbnail(url="https://cdn.discordapp.com/embed/avatars/0.png")
                embed.set_footer(text="by https://www.Bad-Timing.eu")
                channel = client.get_channel(906898823044014130)
                await channel.send(embed=embed)


        sheet = service.spreadsheets()              #Tabelle=Todo(Bauen)
        result = sheet.values().get(spreadsheetId=SAMPLE_SPREADSHEET_ID,
                                    range=Tabelle_4).execute()
        values = result.get('values', [])
 
        if not values:
            print('No data found.')
        else:
            for row in values:
                if not row or row[0] != 'FALSE' or len(row[1]) == 0:
                    continue
                embed = discord.Embed(title='Folgende Daten gefunden: ',
                                      description='{}: {}'.format(row[1], row[2]),
                                      color=0x22a7f0)

                embed.set_thumbnail(url="https://cdn.discordapp.com/embed/avatars/0.png")
                embed.set_footer(text="by https://www.Bad-Timing.eu")
                channel = client.get_channel(906898916639920148)
                await channel.send(embed=embed)

        sheet = service.spreadsheets()              #Tabelle=Todo(Info-Bauen)
        result = sheet.values().get(spreadsheetId=SAMPLE_SPREADSHEET_ID,
                                    range=Tabelle_5).execute()
        values = result.get('values', [])

        if not values:
            print('No data found.')
        else:
            for row in values:
                if not row or row[0] != 'FALSE' or len(row[1]) == 0:
                    continue
                embed = discord.Embed(title='Folgende Daten gefunden: ',
                                      description='{}: {}'.format(row[1], row[2]),
                                      color=0x22a7f0)
                embed.set_thumbnail(url="https://cdn.discordapp.com/embed/avatars/0.png")
                embed.set_footer(text="by https://www.Bad-Timing.eu")
                channel = client.get_channel(906899000047861790)
                await channel.send(embed=embed)

        sheet = service.spreadsheets()              #Tabelle=Todo(Entwickeln)
        result = sheet.values().get(spreadsheetId=SAMPLE_SPREADSHEET_ID,
                                    range=Tabelle_6).execute()
        values = result.get('values', [])

        if not values:
            print('No data found.')
        else:
            for row in values:
                if not row or row[0] != 'FALSE' or len(row[1]) == 0:
                    continue
                embed = discord.Embed(title='Folgende Daten gefunden: ',
                                      description='{}: {}'.format(row[1], row[2]),
                                      color=0x22a7f0)
                embed.set_thumbnail(url="https://cdn.discordapp.com/embed/avatars/0.png")
                embed.set_footer(text="by https://www.Bad-Timing.eu")
                channel = client.get_channel(906899062006116372)
                await channel.send(embed=embed)

if __name__ == '__main__':
    main()


client.run('OTA3MTY1ODIyNzcyOTg1ODg5.YYjOAg.R2yVF4zI81nSQGhX_NNJREuj2q8')

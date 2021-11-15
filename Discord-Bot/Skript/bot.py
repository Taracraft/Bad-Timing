# -*- coding:utf-8 -*-
# Google
# https://developers.google.com/sheets/api/quickstart/python
#
from __future__ import print_function
import os.path

import columns as columns
from googleapiclient.discovery import build
from google_auth_oauthlib.flow import InstalledAppFlow
from google.auth.transport.requests import Request
from google.oauth2.credentials import Credentials
# discord
import asyncio
import discord
import datetime
import logging
from discord import Member
from discord.ext.commands import MissingPermissions, bot, has_permissions

intents = discord.Intents.all()
intents.members = True
intents.messages = True
intents.presences = True
client = discord.Client(intents=discord.Intents.all())

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
        await client.change_presence(activity=discord.Game('@Taracraft'), status=discord.Status.online)
        await asyncio.sleep(5)


g = None


@client.event
async def on_ready():
    global g
    print("Bot is ready!")
    print("Logged in as: " + client.user.name)
    print("Bot ID: " + str(client.user.id))
    g = client.get_guild(759212028744695808)
    for guild in client.guilds:
        print("Connected to server: {}".format(guild))
    print("------")
    client.loop.create_task(status_task())


@client.event
async def on_member_join(member: discord.Member):
    role = discord.utils.get(member.guild.roles, name="Spieler")
    channel = client.get_channel(812581364799897621)
    if member.guild.id == 759212028744695808:
        await member.add_roles(role)
        await channel.send("**Hey! %s**\n Willkommen auf dem %s Discord Server von Bad-Timing%s! \n Viel Spaß!")


@client.event
async def on_message(message):
    global g
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
    else:
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


# google-sheet

# If modifying these scopes, delete the file token.json.
SCOPES = ['https://www.googleapis.com/auth/spreadsheets.readonly']

# The ID and range of a sample spreadsheet.
SAMPLE_SPREADSHEET_ID = '1dwI3YjMYSsyr3PbkYN5LXKP-cAkXpbLsSmpo776beRE'
SAMPLE_RANGE_NAME = ("To-Do(Allgemein)!A1:B12")


def main():
    """Shows basic usage of the Sheets API.
    Prints values from a sample spreadsheet.
    """
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

    # Call the Sheets API
    sheet = service.spreadsheets()
    result = sheet.values().get(spreadsheetId=SAMPLE_SPREADSHEET_ID,
                                range=SAMPLE_RANGE_NAME).execute()
    values = result.get('values', [])

    if not values:
        print('No data found.')
    else:
        print('Name, Major:')
        for row in values:
            # Print columns A and E, which correspond to indices 0 and 4.
            print(len(row))
            print('%s' % (row[1]))


if __name__ == '__main__':
    main()

client.run('OTA3MTY1ODIyNzcyOTg1ODg5.YYjOAg.R2yVF4zI81nSQGhX_NNJREuj2q8')

import json

import discord
from discord import client
from discord.ext.tasks import loop
import twitch


#bot = commands.Bot(command_prefix="$")
intents = discord.Intents.all()
intents.members = True
intents.messages = True
intents.presences = True
client = discord.Client(intents=discord.Intents.all())
g = client.get_guild(759212028744695808)

import json
from datetime import datetime

import requests

with open("config.json") as config_file:
    config = json.load(config_file)


def get_app_access_token():
    params = {
        "client_id": config["client_id"],
        "client_secret": config["client_secret"],
        "grant_type": "client_credentials"
    }

    response = requests.post("https://id.twitch.tv/oauth2/token", params=params)
    access_token = response.json()["access_token"]
    return access_token
# alle 60 tage ausführen Heute 16.11.2021 Nächstesmal 16.01.2021
#access_token = get_app_access_token()
#print(access_token)

def get_users(login_names):
    params = {
        "login": login_names
    }
    headers = {
        "Authorization": "Bearer {}".format(config["access_token"]),
        "Client-Id": config["client_id"]
    }
    responce = requests.get("https://api.twitch.tv/helix/users", params=params, headers=headers)
    print(responce.status_code)
    print(responce.text)
    return {entry["login"]: entry["id"] for entry in responce.json()["data"]}


def get_streams(users):
    params = {
        "user_id": users.values()
    }

    headers = {
        "Authorization": "Bearer {}".format(config["access_token"]),
        "Client-Id": config["client_id"]
    }

    response = requests.get("https://api.twitch.tv/helix/streams", params=params, headers=headers)
    print(response.status_code)
    print(response.text)
    return {entry["user_login"]: entry for entry in response.json()["data"]}
online_users = {}


def get_notifications():
    users = get_users(config["watchlist"])
    streams = get_streams(users)
    print(users)
    print(streams)
    notifications = []
    for user_name in config["watchlist"]:
        if user_name not in online_users:
            online_users[user_name] = datetime.utcnow()
            print(online_users)

        if user_name not in streams:
            online_users[user_name] = None
            print(online_users)
        else:
            started_at = datetime.strptime(streams[user_name]["started_at"], "%Y-%m-%dT%H:%M:%SZ")
            if online_users[user_name] is None or started_at > online_users[user_name]:
                notifications.append(streams[user_name])
                online_users[user_name] = started_at
                print(online_users)
        return notifications




#@bot.command()
#async def ping(ctx):
#    await ctx.send("pong")


@loop(seconds=90)
@client.event
async def check_twitch_online_streamers():
    global g
    channel = client.get_channel(910123877802311720)
    print(channel, 'Kanal')
    if not channel:
        return

    notifications = get_notifications()
    print(notifications)
    for notification in notifications:
        print(notification, 'Benachrichtigungen')
        await channel.send("streamer {} ist jetzt online: {}".format(notification["user_login"], notification))


with open("config.json") as config_file:
    config = json.load(config_file)

if __name__ == "__main__":
    check_twitch_online_streamers.start()
    client.run(config["discord_token"])
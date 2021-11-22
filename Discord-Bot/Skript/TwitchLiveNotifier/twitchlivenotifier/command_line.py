import twitchlivenotifier


def main():
    twitchlivenotifier.channel_clear()
    twitchlivenotifier.config()
    twitchlivenotifier.get_lock()
    twitchlivenotifier.main()

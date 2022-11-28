import os
import csv
import mysql.connector


connection = mysql.connector.connect(
    user='root',
    password='@Kingpin12',
    host='localhost',
    database="tennisDB"
)

cursor = connection.cursor()

tournaments = {}
players = {}



class Tournament:
    def __init__(self, tourney_name, surface, draw_size, tourney_level, date):
        self.tourney_name = tourney_name
        self.surface = surface
        self.draw_size = draw_size
        self.tourney_level = tourney_level
        self.date = date


class Player:
    def __init__(self, name, hand, nationality):
        self.name = name
        self.hand = hand
        self.nationality = nationality


class Match:
    def __init__(self, tourney_id, date, score, num_sets, round, minutes):
        self.tourney_id = tourney_id
        self.date = date,
        self.score = score
        self.num_sets = num_sets
        self.round = round
        self.minutes = minutes


class Match_Stats:
    def __init__(self, player_id, player_ht, age, num_aces, num_df, outcome, match_id):
        self.player_id = player_id
        self.player_ht = player_ht
        self.age = age
        self.num_aces = num_aces
        self.num_df = num_df
        self.outcome = outcome
        self.match_id = match_id
# ADD ALL RELEVANT FILE NAMES TO AN ARRAY


def get_files(directory):
    matches = []
    for filename in os.listdir(directory):
        if "atp_matches_1" in filename or "atp_matches_2" in filename:
            matches.append(filename)
        else:
            continue
    matches.remove('atp_matches_2021.csv')
    return matches

# PARSE ALL FILES AND FILL DICTIONARIES
def parse_data(directory, all_matches):
    match_id = 1
    for matches in all_matches:
        with open(directory + matches, 'r') as file:
            reader = csv.reader(file)
            for row in reader:

                if 'tourney_id' in row[0]:
                    pass
                else:
                    if row[0] not in tournaments:
                        tournaments[row[0]] = Tournament(
                            row[1], row[2], row[3], row[4], row[5])
                        query = "INSERT INTO Tournaments VALUES (\"{}\", \"{}\", \"{}\", \"{}\", \"{}\", \"{}\", \"{}\"); \n".format(
                        row[0], row[1], row[5], row[5][0:4], row[3], row[2], row[4])
                        cursor.execute(query, multi=False)
                    if row[7] not in players:
                        if(row[11] == ''):
                            x = 'U'
                        else:
                            x = row[11]
                        players[row[7]] = Player(row[10], row[11], row[13])
                        query = "INSERT INTO Player VALUES (\"{}\", \"{}\", \"{}\", \"{}\"); \n".format(
                        row[7], row[10], row[13], x)
                        cursor.execute(query, multi=False)
                    if row[15] not in players:
                        if(row[19] == ''):
                            x = 'U'
                        else:
                            x = row[19]
                        players[row[15]] = Player(row[18], row[19], row[21])
                        query = "INSERT INTO Player VALUES (\"{}\", \"{}\", \"{}\", \"{}\"); \n".format(
                        row[15], row[18], row[21], x)
                        cursor.execute(query, multi=False)
                
                    query = "INSERT INTO Tennis_Match VALUES (\"{}\", \"{}\", \"{}\", \"{}\", \"{}\", \"{}\"); \n".format(
                    match_id, row[5], row[23], row[0], row[25], row[26])

                    cursor.execute(query, multi=False)

                    if(row[27] == ''):
                        x = 0
                    else:
                        x = row[27]
                    
                    if(row[28] == ''):
                        y = 0
                    else:
                        y = row[28]

                    if(row[14] == ''):
                        z = 0
                    else:
                        z = row[14]
                    query = "INSERT INTO Match_Stats VALUES (\"{}\", \"{}\", \"{}\", \"{}\", \"{}\", \"{}\", \"{}\"); \n".format(
                    match_id, row[7], z, row[12], x, y, 'W')

                    cursor.execute(query, multi=False)
                    if(row[35] == ''):
                        x = 0
                    else:
                        x = row[35]
                    
                    if(row[36] == ''):
                        y = 0
                    else:
                        y = row[36]
                    if(row[22] == ''):
                        z = 0
                    else:
                        z = row[22]
                    query = "INSERT INTO Match_Stats VALUES (\"{}\", \"{}\", \"{}\", \"{}\", \"{}\", \"{}\", \"{}\"); \n".format(
                    match_id, row[15], z, row[20], x, y, 'L')

                    cursor.execute(query, multi=False)

                    match_id += 1
    cursor.close()


    connection.commit()
    cursor.close()
    connection.close()
    # TESTING TO SEE IF DICTIONARIES FILLED
    # count = 0
    # for key in matches_dict:
    #     # print(str(key) + " | " + str(tournaments[key].date))
    #     print(matches_dict[key].date)
    #     print(tournaments[matches_dict[key].tourney_id].date)
    #     count += 1
    # print(count)

"""
def write_files():
    with open("inserts.sql", "w") as inserts:
        for key in players:
            inserts.write("INSERT INTO Player VALUES (\"{}\", \"{}\", \"{}\", \"{}\"); \n".format(
                key, players[key].name, players[key].nationality, players[key].hand))
        for key in tournaments:
            inserts.write("INSERT INTO Tournaments VALUES (\"{}\", \"{}\", \"{}\", \"{}\", \"{}\", \"{}\"); \n".format(
                key, tournaments[key].tourney_name, tournaments[key].date, tournaments[key].draw_size, tournaments[key].surface, tournaments[key].tourney_level))
        for key in matches_dict:
            inserts.write("INSERT INTO Tennis_Match VALUES (\"{}\", \"{}\", \"{}\", \"{}\", \"{}\", \"{}\"); \n".format(
                key, tournaments[matches_dict[key].tourney_id].date, matches_dict[key].score, matches_dict[key].tourney_id, matches_dict[key].round, matches_dict[key].minutes))
        for key in match_stats:
            inserts.write("INSERT INTO Match_Stats VALUES (\"{}\", \"{}\", \"{}\", \"{}\", \"{}\", \"{}\", \"{}\"); \n".format(
                match_stats[key].match_id, match_stats[key].player_id, match_stats[key].age, match_stats[key].player_ht, match_stats[key].num_aces, match_stats[key].num_df, match_stats[key].outcome))


def import_data():
    file = open("inserts.sql", "r")
    schema_string = file.read()
    file.close()

    try:
        result_iterator = cursor.execute(schema_string, multi=True)
        for r in result_iterator:
            pass

    except mysql.connector.Error as error_descriptor:
        if error_descriptor.errno == mysql.connector.errorcode.ER_TABLE_EXISTS_ERROR:
            print("Table already exists: {}".format(error_descriptor))
        else:
            print("Failed creating schema: {}".format(error_descriptor))
        exit(1)"""

def main():
    directory = "/Users/owencorkery/Documents/Fall 2021/CSC 353/hw3/tennis_atp-master/"
    all_matches = get_files(directory)
    parse_data(directory, all_matches)


if __name__ == '__main__':
    main()

# INSERT INTO Player VALUES ('winner_id', 'winner_name', 'winner_ioc', 'winner_hand')
# INSERT INTO Tournaments VALUES ('tourney_id', 'tourney_name', 'tourney_date', 'draw_size', 'surface', 'tourney_level')
# INSERT INTO Tennis_Match VALUES ('mach_id', 'tourney_date', 'score', 'tourney_id', 'round', 'minutes')
# INSERT INTO Match_Stats VALUES ('match_id', 'player_id', 'player_age', 'player_ht', 'num_ace', 'num_df', 'outcome')

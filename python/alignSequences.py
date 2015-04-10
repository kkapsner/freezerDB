import sys

if (len(sys.argv) < 3):
	sys.exit(1);


DOWN = 0
LEFT = 1
DIAG = 2
MATCH = 3
REPLACE = 4

standardTrackingMatching = ["|", "-", "", "=", "X"]

def weighting(c1, c2, x, y, lastTracking):
	if (c1 == c2):
		return 1
	else:
		if (lastTracking >= DIAG):
			return -2
		else:
			return -1

def initialise():
	lastScore = scores[0][0];
	for x in range(1, length1 + 1):
		lastScore = lastScore + weighting(
			sequence1[x - 1], "", x, 0, LEFT
		);
		scores[x][0] = lastScore
		tracking[x][0] = LEFT
	
	lastScore = scores[0][0];
	for y in range(1, length2 + 1):
		lastScore = lastScore + weighting(
			"", sequence2[y - 1], 0, y, DOWN
		);
		scores[0][y] = lastScore
		tracking[0][y] = DOWN

def run():
	for x in range(1, length1 + 1):
		for y in range(1, length2 + 1):
			c1 = sequence1[x - 1]
			c2 = sequence2[y - 1]
			
			directions = [
				scores[x][y - 1] + weighting("", c2, x, y, tracking[x][y - 1]),
				scores[x - 1][y] + weighting(c1, "", x, y, tracking[x - 1][y]),
				scores[x - 1][y - 1] + weighting(c1, c2, x, y, tracking[x - 1][y - 1])
			]
			maximalKey = directions.index(max(directions))
			scores[x][y] = directions[maximalKey];
			if (maximalKey == DIAG):
				if (scores[x][y] > scores[x - 1][y - 1]):
					maximalKey = MATCH;
				else:
					maximalKey = REPLACE;
			
			tracking[x][y] = maximalKey;

sequence1 = sys.argv[1]
sequence2 = sys.argv[2]
length1 = len(sequence1)
length2 = len(sequence2)

tracking = [[0 for x in xrange(length2 + 1)] for x in xrange(length1 + 1)]
scores = [[0 for x in xrange(length2 + 1)] for x in xrange(length1 + 1)] 


initialise()
run()

# print scores
# print tracking

print "Score: %d" % scores[length1][length2]

trackingString = ""
x = length1
y = length2
while (x > 0) or (y > 0):
	currentTracking = tracking[x][y]
	trackingString = standardTrackingMatching[currentTracking] + trackingString
	if (currentTracking == DOWN):
		y -= 1
	elif (currentTracking == LEFT):
		x -= 1
	elif (
		currentTracking == DIAG or
		currentTracking == MATCH or
		currentTracking == REPLACE
	):
		x -= 1
		y -= 1
	else:
		raise ValueError("Invalid tracking.")

print "Tracking string: " + trackingString


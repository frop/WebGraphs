#! /usr/bin/env python

import matplotlib.pyplot as plt

import sys
from lib import *

#> python color_nodes.py 000000

graph_json = load_json_graph(sys.argv[1])

if 'error' in graph_json:
	print graph_json['error']
	exit();
	
G = json_to_nx(graph_json)

pos=nx.spring_layout(G) # positions for all nodes

node_colors = []

for v in G.nodes(data=True):
	if (v[1]['group'] > 0):
		node_colors.append('blue')
	else:
		node_colors.append('yellow')

# nodes
nx.draw_networkx_nodes(G,pos,node_size=200, node_color=node_colors)

# edges
nx.draw_networkx_edges(G,pos)

# labels
nx.draw_networkx_labels(G,pos,font_size=10,font_family='sans-serif')

plt.axis('off')
#plt.savefig("weighted_graph.png") # save as png
plt.show() # display


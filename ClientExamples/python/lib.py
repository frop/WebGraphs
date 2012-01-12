import urllib2
import simplejson
from networkx import *

def load_json_graph(graph_id):
	req = urllib2.Request("http://www.rig.com/graph/"+graph_id+".json")
	opener = urllib2.build_opener()
	f = opener.open(req)

	H = simplejson.load(f)
	
	return H

def json_to_nx(H):
	G = nx.Graph()
	for v in H['vertexes']:
		data = H['vertexes'][v]
		G.add_node(v, data)
	
	for v in H['adjacency']:
		for u in H['adjacency'][v]:
			data = H['adjacency'][v][u]
			G.add_edge(v, u, data)
			
	return G

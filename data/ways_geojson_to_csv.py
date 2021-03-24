import functools,json
data=json.load(open("ways.geojson"))
d=dict()
for i in data["features"]:
    name=i["properties"]["name"]
    if name not in d:
        d[name]=[]
    d[name].append(i["id"])
def sort(data):
    if len(data)==0:
        return []
    if len(data)==1:
        return data
    left=sort(data[:len(data)//2])
    right=sort(data[len(data)//2:])
    res=[]
    while len(left)>0 and len(right)>0:
        if left[0][0]<right[0][0]:
            res.append(left.pop(0))
        else:
            res.append(right.pop(0))
    while len(left)>0:
        res.append(left.pop(0))
    while len(right)>0:
        res.append(right.pop(0))
    return res
data = sort(list(d.items()))
data = map(lambda x: [x[0],";".join(str(i) for i in x[1])],data)
data = map(lambda x: x[0]+";"+x[1],data)
f=open("ways.csv","w")
data="\n".join(data)
f.write(data.encode(encoding="utf-8"))
f.close()
#print(list(d.items()))

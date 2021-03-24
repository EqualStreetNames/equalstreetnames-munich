files=["data\\finished.txt"]
for file in files:
    file_obj=open(file,"r")
    file_data=file_obj.read()
    file_obj.close()
    file_obj=open(file,"wb")
    file_obj.write(file_data.encode(encoding="utf-8"))
    file_obj.close()

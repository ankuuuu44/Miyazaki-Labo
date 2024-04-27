import sys

#passの設定（相対パス）
sys.path.append('c:\\users\\tomoya\\anaconda3\\lib\\site-packages')
import csv
import random
import collections
import pandas as pd

# scikit-learnの分類器構築関係のメソッド
from sklearn.ensemble import RandomForestClassifier
from sklearn.naive_bayes import GaussianNB
from sklearn import svm

from sklearn.neural_network import MLPClassifier

# scikit-learnの，正解率とか精度とか出すためのメソッド
from sklearn import metrics
from sklearn.metrics import classification_report
from sklearn.metrics import confusion_matrix
from sklearn.metrics import accuracy_score

from sklearn import model_selection
#from sklearn import cross_validation

#深層学習関連のライブラリ
import numpy as np
#import matplotlib.pyplot as plt
#from keras.models import Sequential
#from keras.layers import Dense, Activation
#from sklearn.model_selection import train_test_split
#from keras.utils import np_utils

# 数値計算のためのライブラリ．実質，numpy.array()というのものを使うためだけにインポートしてます．
# newArray = numpy.array(oldArray)という風に使うと，oldArrayが，numpy形式の配列に変換されて，newArrayに代入されます．
# numpy形式の配列は，[1, 2, 3] + [4, 5, 6]というような形で足し算ができて，この場合，結果は[5, 7, 9]になります．
import numpy

from operator import itemgetter

import time

outputfile_dir = 'C:/xampp/htdocs/yamakawa/sien/Python/inputdata/'
outputfile = '2019suoutputT_range2.csv'
outputfilename = outputfile_dir + outputfile


def hesitateClassify(concatdf,testdf):   #10分割交差検定で迷い推定を行う関数

    features = [   
        'time', 
        'distance',     
        'averagespeed', 
        'maxspeed', 
        'thinkingtime', 
        'answeringtime', 
        'maxstoptime',
        'totalDDintervaltime',
        'maxDDintervaltime',
        'maxDDtime',
        'DDcount',
        'xuturncount',
        'yuturncount']

    Y = ['understand']  # 予測する対象のラベル

    #特徴量がX_dataに，正解ラベルがy_dataに格納される．
    Xtrain_data = concatdf[features]
    ytrain_data = concatdf[Y].to_numpy().ravel()
    
    Xtest_data = testdf[features]
    ytest_data = testdf[Y]


    rfc = RandomForestClassifier(random_state=0)
    rfc.fit(Xtrain_data, ytrain_data)
    labels_pred = rfc.predict(Xtest_data)

    for label in labels_pred:
        if label == 2:
            print("hesitate True")
        elif label == 4:
            print("hesitate False")
        else :
            print("Undefined label:" , label)
    
    
    

                           


def tfclass(df,df1):#読み込んだデータフレームを迷い有りと無しに分ける関数
    #もし，教師データをcsvファイルにまとめてオープンするだけとかならこの関数要らないかもね
    praunderstand = [2,4]

    df_classifyT = df1[df1['understand'].isin([4])]
    df_classifyF = df1[df1['understand'].isin([2])]

    numdfT = df_classifyT.shape[0]  #df_classifyTの行数
    numdfF = df_classifyF.shape[0]  #同じく行数

    #とりあえず2019年度の実験の迷い

    if numdfF > numdfT :
        newdf = df_classifyF.sample(n=numdfT, random_state = 1)
        concatdf = pd.concat([newdf,df_classifyT])
    else :
        newdf = df_classifyT.sample(n=numdfF, random_state = 1)
        concatdf = pd.concat([newdf,df_classifyF])

    numtfdata = concatdf.shape[0]

    return (df_classifyF, numdfF, df_classifyT, numdfT, concatdf, numtfdata)


def main():
    df = pd.read_csv(outputfilename, header = None)
    X_new = np.array([[sys.argv[1],sys.argv[2],sys.argv[3],sys.argv[4],sys.argv[5],sys.argv[6],sys.argv[7],sys.argv[8],sys.argv[9],sys.argv[10],sys.argv[11],sys.argv[12],sys.argv[13],sys.argv[14],sys.argv[15],sys.argv[16],sys.argv[17],sys.argv[18],sys.argv[19],sys.argv[20],sys.argv[21],sys.argv[22],sys.argv[23],sys.argv[24],sys.argv[25],sys.argv[26],sys.argv[27],sys.argv[28],sys.argv[29],sys.argv[30],sys.argv[31],sys.argv[32],sys.argv[33],sys.argv[34],sys.argv[35]]])
    testdf = pd.DataFrame(data = X_new)

    new_columns = [
                'uid', 
                'wid', 
                'understand',
                'date',
                'check',
                'time', 
                'distance', 
                'averagespeed',
                'maxspeed',
                'thinkingtime', 
                'answeringtime',
                'totalStopTime',
                'maxstoptime',
                'totalDDintervaltime',
                'maxDDintervaltime',
                'maxDDtime',
                'minDDtime',
                'DDcount',
                'groupingDDcount',
                'groupingcountbool',
                'xuturncount',
                'yuturncount',
                'register_move_count1',
                'register_move_count2',
                'register_move_count3',
                'register_move_count4',
                'register01count1',
                'register01count2',
                'register01count3',
                'register01count4',
                'registerDDCount',
                'stopcount',
                'xUTurnCountDD',
                'yUTurnCountDD',
                'FromlastdropToanswerTime'
    ]

    #ヘッダをnew_columnsに変更
    df.columns = new_columns
    testdf.columns = new_columns
    
    #dffreme = pd.DataFrame(df)
    df1 = df.copy()     #オリジナルのデータフレームはdfにある．使用するのはdf1


    #tfdf[0]にはかなり迷ったのdf,1は迷い有りの数
    # 2には迷い無しdf，3には迷い無し数が入っている．
    #4には1:1で作成したデータフレーム，5にはその行数（データ数）が入っている．
    tfdf = tfclass(df,df1)

    classify = hesitateClassify(tfdf[4],testdf)
    






if __name__ == '__main__':
    main()
    
    
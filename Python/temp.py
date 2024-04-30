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



def main():
    X_new = np.array([[sys.argv[1],sys.argv[2],sys.argv[3],sys.argv[4],sys.argv[5],sys.argv[6],sys.argv[7],sys.argv[8],sys.argv[9],sys.argv[10],sys.argv[11],sys.argv[12],sys.argv[13],sys.argv[14],sys.argv[15],sys.argv[16],sys.argv[17],sys.argv[18],sys.argv[19],sys.argv[20],sys.argv[21],sys.argv[22],sys.argv[23],sys.argv[24],sys.argv[25],sys.argv[26],sys.argv[27],sys.argv[28],sys.argv[29],sys.argv[30],sys.argv[31],sys.argv[32],sys.argv[33],sys.argv[34],sys.argv[35]]])
    print(X_new.shape[1])




if __name__ == '__main__':
    main()
    

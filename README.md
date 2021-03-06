## 动态交通流分配的PHP版本
* 该项目为交通工程本科课程中的一个延伸拓展，实现了交通小区之间的流量分配计算，以及保存具体的计算过程。
* 流量分配方法为改进的多路径分配模型（改进Logit型的路径选择模型）

## 如何运行？
* 配置PHP环境
* 填写`/doc`目录下三个基础数据文件（xls格式，填写前请阅读readme.doc说明）
* 访问程序首页即可生成对应的交通小区计算矩阵，点击自动计算后，程序会以7s一次的速度开始计算个小区形心之间的分配过程，并将计算结果与计算过程写入相应的缓存文件中。当计算步骤完成后，相应的按钮即变为黑色。
* 当所有小区形心之间的计算全部完成时，点击路网交通量分配汇总按钮，程序会自动整理出所有计算过程中各个路段应分配的交通量的总和，汇总至Excel格式文件并输出至浏览器提供下载。该过程速度较快，经测试输出过程一般为1s左右。

## 界面展示
![主界面](https://raw.githubusercontent.com/resuly/Traffic-Flow-Distribution-with-PHP/master/images/index.png)

![计算过程](https://raw.githubusercontent.com/resuly/Traffic-Flow-Distribution-with-PHP/master/images/progress.png)

![交通小区信息](https://raw.githubusercontent.com/resuly/Traffic-Flow-Distribution-with-PHP/master/images/zone.png)

![路网信息](https://raw.githubusercontent.com/resuly/Traffic-Flow-Distribution-with-PHP/master/images/Roads.jpg)


## Reference
[参考资料](images/交通流分配.pdf)

## License
* Code of this project is licensed under the [MIT License](https://github.com/racaljk/hosts/blob/master/LICENSE)

[TOC]

### Random Password Generator

简易的随机密码生成工具，支持自定义掩码

#### Using

所有的选项通过`URL`的组织来实现，如
```html
http://aki.so/tools/rpass/18
```
该命令输出长度为18，由大小写祖母和数字组成的密码串
#### Options
##### {int} 长度
```html
http://aki.so/tools/rpass/36
<!-- 输出 -->
R5T4GMeut5VbNQuZLl9hzheLtEeODs03zeJE
```
##### x{int} 数量
```html
http://aki.so/tools/rpass/36/x5
<!-- 输出 -->
RuODMOtciUlSYt27jkzSsXjCCv4m7hpt2n7g
CCjFAlcYBZo9fAcSKzVgHGy6EJVTwRhv6Wcg
14FpHQuW1r0JY0y2D9hECdzRMpLiErppGkQZ
CJ87I0U8MeXI8bc3plYmzrCKLYfWuRh5xynS
3F7FVx0lK0BCqOnuWVzychkFhMuub20N01tM
```
##### {number|normal|strong|extreme} 复杂度
- **number** 纯数字
- **normal** 大小写字母和数字
- **strong** 大小写字母、数字和下划线
- **extreme** 大小写字母、数字、下划线以及特殊字符

```html
http://aki.so/tools/rpass/36/extreme
<!-- 输出 -->
o$2VdB_4+CVdOT"DNuH`uOr__}'P(>&C?T[_
```
##### {norepeat} 无重复模式

该模式中的下划线不受影响

```html
http://aki.so/tools/rpass/36/strong/norepeat
<!-- 输出 -->
KHbDnQ9l_PLzZT0aJFmr_3jy_voSRcNp_q1A
```

##### {chinese} 中文汉字模式
使用chinese模式时，除了长度参数有效外，其他均无效
```html
http://aki.so/tools/rpass/36/chinese
<!-- 输出 -->
弄磕蔫看沾跌毫贝谨拣搞衫懒抓停奔等瘦乏勋糊币僵袜挨牡彤漂糜阜贫赔纱筋撼昭
```

##### {D|U|L|S|-|_} 自定义掩码
使用chinese模式时，除了长度参数有效外，其他均无效

"L" "abcdefghijklmnopqrstuvwxyz"
"U" "ABCDEFGHIJKLMNOPQRSTUVWXYZ"
"D" "0123456789"
"S" "@#$%^&*()"

```html
http://aki.so/tools/rpass/UUUU-LLLL-DDDD
<!-- 输出 -->
RRIL-pvbc-9822
```

#### 说明

* 各选项不分顺序。
* 无重复模式长度不能无限长，取决于密码构成。
* 密码个数不能超过100，否则以100计。
* 当强度为strong及以上时，每8位密码必将随机产生一个下划线。

#### URL重写配置
```bash
RewriteRule ^(?!index.php)(.*)$ index.php?method=$1 [L]
```
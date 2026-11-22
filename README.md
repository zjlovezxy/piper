# Piper Media Player (MyPHP)

Piper 是对标 macOS [IINA](https://iina.io) 的现代全功能原生音视频播放器，基于 [MyPHP](https://github.com/owner888/myphp) 构建。

本仓库源自 [owner888/iina](https://github.com/owner888/iina)（上游作者 @owner888），重命名为 **Piper** 并加入 macOS / Linux / Windows 三端 CI 构建。

Piper is a modern, hardware-accelerated media player modeled after IINA, built in pure PHP 8.4+ / 8.5 with [MyPHP](https://github.com/owner888/myphp).

![Piper Media Player Screenshot](screenshot.jpg)

## 特性 (Features)

- **硬件加速解码与 HDR**：支持 MKV、MP4、MOV、WebM、TS 及 HTTP/HLS 流媒体，10-bit HDR 与色调映射（Tone Mapping）、反交错（Deinterlace）。
- **Liquid Glass 现代界面**：悬浮毛玻璃控制栏（OSC）、缩略图进度预览、快速设置侧边栏（视频/音频/字幕调节、10 段均衡器、裁剪/旋转/画幅比）、播放列表与章节面板、音乐模式（Mini Player）。
- **libass 级 ASS/SSA 特效字幕**：完整支持自定义字体、描边、阴影、`\pos`、`\move`、`\t` 关键帧动画、`\k`/`\kf` 卡拉 OK 扫字、3D 透视旋转（`\frx`/`\fry`）与矢量绘图裁剪（`\p`/`\clip`），渲染于毛玻璃控制栏下方。
- **插件系统与插件商店**：内置在线字幕搜索（OpenSubtitles）、yt-dlp 在线视频解析与用户脚本插件，支持 GitHub 插件一键安装与 JS/PHP 开发者控制台。
- **桌面深度集成**：欢迎窗口、播放历史记录、画中画（Picture-in-Picture）、媒体键与系统 Now Playing 控制中心集成。

## 运行 (Run)

需要 PHP 8.4+（含 `ext-ffi`）与 `myphp` 框架检出（默认在 `../myphp`，可在 `myphp.json` 的 `framework` 字段配置）：

```sh
myphp run -d macos
# 或直接打开指定视频文件 / 流媒体地址：
myphp run -d macos /path/to/video.mkv
```

## 打包发布 (Build)

```sh
myphp build -d macos
```

## 自动构建与下载 (CI)

本仓库用 GitHub Actions 编译三端（产物版本取自 `myphp.json`，下例为 `1.0.0`）：

| 平台 | 产物 |
|---|---|
| macOS（arm64 / amd64） | `Piper 1.0.0 arm64.dmg`、`Piper 1.0.0 amd64.dmg`（ad hoc 签名） |
| Linux（amd64 / arm64） | `piper_1.0.0_amd64.deb`、`piper_1.0.0_arm64.deb`、`piper-1.0.0-linux-<arch>.tar.gz`、`PKGBUILD`，以及静态单文件 `piper`、`.desktop` 与图标 |
| Windows（amd64） | `piper-setup-1.0.0-amd64.exe`（NSIS 安装包）、`piper-1.0.0-windows-amd64.zip`（绿色版） |

- 推到 `main` 或提 PR：自动编译三端，产物在 `Actions` → `Build` 该次运行的 Artifacts 里。
- 发布版本：打标签即可自动编译并创建 Release、上传全部安装包：

  ```sh
  git tag v1.0.1 && git push origin v1.0.1
  ```

- 构建用的框架是 `owner888/myphp`，版本由 `.github/workflows/build.yml` 的 `MYPHP_REF` 指定（当前 `v1.203.11`）；需要跟随框架升级时改这一个值。
- 未做代码签名/公证：macOS 首次打开需右键“打开”（或 `xattr -dr com.apple.quarantine /Applications/Piper.app`），Windows 安装包会触发 SmartScreen 提示。

## 测试 (Test)

```sh
php tests/run.php
```

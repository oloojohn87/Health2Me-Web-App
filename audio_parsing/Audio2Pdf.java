import java.io.FileOutputStream;
import java.util.Date;

import com.itextpdf.text.Anchor;
import com.itextpdf.text.BadElementException;
import com.itextpdf.text.BaseColor;
import com.itextpdf.text.Chapter;
import com.itextpdf.text.Document;
import com.itextpdf.text.DocumentException;
import com.itextpdf.text.Element;
import com.itextpdf.text.Font;
import com.itextpdf.text.List;
import com.itextpdf.text.ListItem;
import com.itextpdf.text.Paragraph;
import com.itextpdf.text.Phrase;
import com.itextpdf.text.Section;
import com.itextpdf.text.pdf.PdfPCell;
import com.itextpdf.text.pdf.PdfPTable;
import com.itextpdf.text.pdf.PdfWriter;


public class Audio2Pdf {
  private static String FILE = "c:/temp/FirstPdf.pdf";
  private static Font catFont = new Font(Font.FontFamily.TIMES_ROMAN, 18,
      Font.BOLD);
  private static Font redFont = new Font(Font.FontFamily.TIMES_ROMAN, 12,
      Font.NORMAL, BaseColor.RED);
  private static Font subFont = new Font(Font.FontFamily.TIMES_ROMAN, 16,
      Font.BOLD);
  private static Font smallBold = new Font(Font.FontFamily.TIMES_ROMAN, 12,
      Font.BOLD);

  public static void main(String[] args) {
    try {
      Document document = new Document();
      PdfWriter.getInstance(document, new FileOutputStream(FILE));
      document.open();
      addMetaData(document);
      addTitlePage(document);
      addContent(document,"hello");
      document.close();
    } catch (Exception e) {
      e.printStackTrace();
    }
  }
  
  
  public void createpdf(String message, String filename){
	  try {
	      Document document = new Document();
	      PdfWriter.getInstance(document, new FileOutputStream(filename));
	      document.open();
	      addMetaData(document);
	      addTitlePage(document);
	      addContent(document,message);
	      document.close();
	    } catch (Exception e) {
	      e.printStackTrace();
	    }
  }

  // iText allows to add metadata to the PDF which can be viewed in your Adobe
  // Reader
  // under File -> Properties
  private static void addMetaData(Document document) {
    document.addTitle("Audio file PDF");
    document.addSubject("Audio file PDF");
    //document.addKeywords("Java, PDF, iText");
    document.addAuthor("Helath2me");
    document.addCreator("Health2me");
  }

  private static void addTitlePage(Document document)
      throws DocumentException {
    Paragraph preface = new Paragraph();
    // We add one empty line
    addEmptyLine(preface, 1);
    // Lets write a big header
    preface.add(new Paragraph("Title of the document", catFont));

    addEmptyLine(preface, 1);
    // Will create: Report generated by: _name, _date
    preface.add(new Paragraph("Report generated by: Health2me , on " + new Date(), //$NON-NLS-1$ //$NON-NLS-2$ //$NON-NLS-3$
        smallBold));
    addEmptyLine(preface, 3);
    preface.add(new Paragraph("This document is automated transcription of the audio recording ",
        smallBold));

    addEmptyLine(preface, 8);

    preface.add(new Paragraph("This document is an automated transcription provided by Health2me.",
        redFont));

    document.add(preface);
    // Start a new page
    document.newPage();
  }

  private static void addContent(Document document,String message) throws DocumentException {
    Anchor anchor = new Anchor("Audio Contents", catFont);
    anchor.setName("Audio Contents");

    // Second parameter is the number of the chapter
    Chapter catPart = new Chapter(new Paragraph(anchor), 1);

    Paragraph subPara = new Paragraph("Speech contents", subFont);
    Section subCatPart = catPart.addSection(subPara);
    subCatPart.add(new Paragraph(message));

   /* subPara = new Paragraph("Subcategory 2", subFont);
    subCatPart = catPart.addSection(subPara);
    subCatPart.add(new Paragraph("Paragraph 1"));
    subCatPart.add(new Paragraph("Paragraph 2"));
    subCatPart.add(new Paragraph("Paragraph 3"));*/

    // Add a list
   /* createList(subCatPart);
    Paragraph paragraph = new Paragraph();
    addEmptyLine(paragraph, 5);
    subCatPart.add(paragraph);*/

    // Add a table
    //createTable(subCatPart);

    // Now add all this to the document
    document.add(catPart);

    // Next section
  /*  anchor = new Anchor("Second Chapter", catFont);
    anchor.setName("Second Chapter");*/

    // Second parameter is the number of the chapter
    /*catPart = new Chapter(new Paragraph(anchor), 1);

    subPara = new Paragraph("Subcategory", subFont);
    subCatPart = catPart.addSection(subPara);
    subCatPart.add(new Paragraph("This is a very important message"));*/

    // Now add all this to the document
   // document.add(catPart);

  }

  private static void createTable(Section subCatPart)
      throws BadElementException {
    PdfPTable table = new PdfPTable(3);

    // t.setBorderColor(BaseColor.GRAY);
    // t.setPadding(4);
    // t.setSpacing(4);
    // t.setBorderWidth(1);

    PdfPCell c1 = new PdfPCell(new Phrase("Table Header 1"));
    c1.setHorizontalAlignment(Element.ALIGN_CENTER);
    table.addCell(c1);

    c1 = new PdfPCell(new Phrase("Table Header 2"));
    c1.setHorizontalAlignment(Element.ALIGN_CENTER);
    table.addCell(c1);

    c1 = new PdfPCell(new Phrase("Table Header 3"));
    c1.setHorizontalAlignment(Element.ALIGN_CENTER);
    table.addCell(c1);
    table.setHeaderRows(1);

    table.addCell("1.0");
    table.addCell("1.1");
    table.addCell("1.2");
    table.addCell("2.1");
    table.addCell("2.2");
    table.addCell("2.3");

    subCatPart.add(table);

  }

  private static void createList(Section subCatPart) {
    List list = new List(true, false, 10);
    list.add(new ListItem("First point"));
    list.add(new ListItem("Second point"));
    list.add(new ListItem("Third point"));
    subCatPart.add(list);
  }

  private static void addEmptyLine(Paragraph paragraph, int number) {
    for (int i = 0; i < number; i++) {
      paragraph.add(new Paragraph(" "));
    }
  }
} 